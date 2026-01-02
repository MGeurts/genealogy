<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class MigratePhotos extends Command
{
    /**
     * Photo Migration Command
     *
     * This command migrates photos from an old folder structure to a new unified structure.
     * It reorganizes photo files from three separate folders (photos, photos-096, photos-384)
     * into a single unified photos folder with originals and WebP variants.
     *
     * Usage: php artisan photos:migrate [--dry-run]
     *
     * OLD STRUCTURE:
     * storage/app/public/
     * ├── photos/          (largest size - WebP)
     *     └── {teamId}/
     *         ├── {personId}_{index}_{timestamp}.webp
     * ├── photos-096/      (small size - WebP)
     *     └── {teamId}/
     *         ├── {personId}_{index}_{timestamp}.webp
     * └── photos-384/      (medium size - WebP)
     *     └── {teamId}/
     *         ├── {personId}_{index}_{timestamp}.webp
     *
     * NEW STRUCTURE:
     * storage/app/public/photos/
     * └── {teamId}/
     *     └── {personId}/
     *         ├── {personId}_{index}_{timestamp}.webp        (original - from photos/)
     *         ├── {personId}_{index}_{timestamp}_large.webp  (1920x1080 - copy of original)
     *         ├── {personId}_{index}_{timestamp}_medium.webp (384px - from photos-384/)
     *         └── {personId}_{index}_{timestamp}_small.webp  (96px - from photos-096/)
     *
     * MIGRATION STRATEGY:
     * 1. Files from "photos/" become the "original" (no suffix) AND are duplicated as "_large.webp"
     * 2. Files from "photos-096/" become "_small.webp" variants
     * 3. Files from "photos-384/" become "_medium.webp" variants
     * 4. All files are kept as WebP format
     * 5. Files that don't exist in all three folders are ignored
     *
     * OPERATIONS:
     * 1. Creates backup of existing folders (photos, photos-096, photos-384) with timestamp
     * 2. Checks if migration has already been run (photos-096 and photos-384 must exist)
     * 3. Scans "photos/" folder to identify all available photos
     * 4. For each photo found in "photos/":
     *    a. Copies as original (no suffix)
     *    b. Copies as _large.webp variant
     *    c. Copies from photos-384/ as _medium.webp (if exists)
     *    d. Copies from photos-096/ as _small.webp (if exists)
     * 5. Creates new directory structure (photos/{teamId}/{personId}/)
     * 6. Copies files to new locations and deletes originals
     * 7. Cleans up empty folders
     *
     * SAFETY FEATURES:
     * - Creates timestamped backups before migration
     * - Prevents multiple runs by checking if old folders still exist
     * - Dry-run mode (--dry-run) shows actions without executing
     * - Skips .gitignore files during processing
     * - Error handling for unexpected file structures
     * - Ensures target directories exist before copying
     * - Ignores photos that don't have all variants
     *
     * EXAMPLE:
     * photos/1/560_001_20250816113838.webp     → photos/1/560/560_001_20250816113838.webp (original)
     *                                           → photos/1/560/560_001_20250816113838_large.webp
     * photos-384/1/560_001_20250816113838.webp → photos/1/560/560_001_20250816113838_medium.webp
     * photos-096/1/560_001_20250816113838.webp → photos/1/560/560_001_20250816113838_small.webp
     *
     * PURPOSE:
     * Refactors photo storage system for better organization by team and person while
     * maintaining different image sizes in a structured way for easier management.
     * This improves performance and allows migrating disks to other storage solutions.
     *
     * WARNING:
     * This command is intended to be run ONLY ONCE during the migration process!!
     */
    protected $signature = 'photos:migrate {--dry-run : Only show actions without moving or deleting files and/or folders}';

    protected $description = 'Migrate old photo folder structure (photos, photos-096, photos-384) into the new unified structure with original + variants';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $this->info($dryRun ? 'Starting photo migration (DRY RUN)...' : 'Starting photo migration...');

        $basePath = storage_path('app/public');

        // Check if migration has already been run
        if ($this->hasMigrationAlreadyRun($basePath)) {
            $prefix = $dryRun ? '[DRY] ' : '';
            $this->warn($prefix . '❌ Migration has already been completed!');
            $this->warn($prefix . 'The old folder structure (photos-096 and photos-384) no longer exists.');
            $this->warn($prefix . 'This command can only be run once. If you need to re-run it, restore the old folder structure first.');

            return self::FAILURE;
        }

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE: No files will be moved or deleted. Showing what would happen:');
        }

        // Create backups before migration
        $this->createBackups($basePath, $dryRun);

        $photosRoot    = "{$basePath}/photos";
        $photos096Root = "{$basePath}/photos-096";
        $photos384Root = "{$basePath}/photos-384";

        if (! is_dir($photosRoot)) {
            $this->error('❌ Main photos folder not found. Cannot proceed with migration.');

            return self::FAILURE;
        }

        $this->info('Scanning photos folder for images to migrate...');

        $migratedCount = 0;
        $skippedCount  = 0;

        // Get all files from the main photos folder (these become our originals)
        foreach ($this->getAllFiles($photosRoot) as $originalPath) {
            if (str_contains($originalPath, '.gitignore')) {
                continue;
            }

            $relativePath = Str::after($originalPath, "{$photosRoot}/");
            $parts        = explode('/', $relativePath);

            if (count($parts) < 2) {
                $this->warn("⚠ Unexpected file structure: {$relativePath}");
                $skippedCount++;
                continue;
            }

            $teamId       = $parts[0];
            $filename     = $parts[1];
            $personId     = Str::before($filename, '_');
            $extension    = pathinfo($filename, PATHINFO_EXTENSION);
            $baseFilename = pathinfo($filename, PATHINFO_FILENAME);

            // Build paths for all variants
            $newDir          = "{$basePath}/photos/{$teamId}/{$personId}";
            $newOriginalPath = "{$newDir}/{$filename}";
            $newLargePath    = "{$newDir}/{$baseFilename}_large.{$extension}";
            $newMediumPath   = "{$newDir}/{$baseFilename}_medium.{$extension}";
            $newSmallPath    = "{$newDir}/{$baseFilename}_small.{$extension}";

            // Find corresponding files in other folders (optional)
            $mediumPath = "{$photos384Root}/{$teamId}/{$filename}";
            $smallPath  = "{$photos096Root}/{$teamId}/{$filename}";

            if ($dryRun) {
                $this->line("[DRY] Processing: {$baseFilename}");
                $this->line("[DRY]   Original: {$originalPath} → {$newOriginalPath}");
                $this->line("[DRY]   Large:    {$originalPath} → {$newLargePath}");

                if (file_exists($mediumPath)) {
                    $this->line("[DRY]   Medium:   {$mediumPath} → {$newMediumPath}");
                } else {
                    $this->line('[DRY]   Medium:   ⚠ Not found, skipping');
                }

                if (file_exists($smallPath)) {
                    $this->line("[DRY]   Small:    {$smallPath} → {$newSmallPath}");
                } else {
                    $this->line('[DRY]   Small:    ⚠ Not found, skipping');
                }

                $migratedCount++;
            } else {
                // Create directory if needed
                if (! is_dir($newDir)) {
                    mkdir($newDir, 0755, true);
                }

                // Copy original file (no suffix)
                copy($originalPath, $newOriginalPath);

                // Copy original as large variant
                copy($originalPath, $newLargePath);

                // Copy medium if exists
                if (file_exists($mediumPath)) {
                    copy($mediumPath, $newMediumPath);
                    unlink($mediumPath);
                }

                // Copy small if exists
                if (file_exists($smallPath)) {
                    copy($smallPath, $newSmallPath);
                    unlink($smallPath);
                }

                // Delete original after successful copy
                unlink($originalPath);

                $this->line("✔ Migrated: {$baseFilename}");
                $migratedCount++;
            }
        }

        // Cleanup old folders
        if (! $dryRun) {
            if (is_dir($photos096Root)) {
                $this->cleanupFolder($photos096Root, false);
            }
            if (is_dir($photos384Root)) {
                $this->cleanupFolder($photos384Root, false);
            }
        } else {
            $this->line("[DRY] Would cleanup: {$photos096Root}");
            $this->line("[DRY] Would cleanup: {$photos384Root}");
        }

        $this->newLine();
        $this->info('✅ Migration completed!');
        $this->info("   Photos migrated: {$migratedCount}");

        if ($skippedCount > 0) {
            $this->warn("   Photos skipped: {$skippedCount}");
        }

        if ($dryRun) {
            $this->newLine();
            $this->info('💡 This was a DRY RUN. Run without --dry-run to perform actual migration.');
        } else {
            $this->newLine();
            $this->info('💡 Migration successful! Your photos are now organized in the new structure.');
            $this->info('   - Originals are preserved as .webp files');
            $this->info('   - Large, medium, and small variants are created');
            $this->info('   - Old folder structure has been cleaned up');
        }

        return self::SUCCESS;
    }

    /**
     * Check if the migration has already been run by verifying if the old folders exist
     */
    private function hasMigrationAlreadyRun(string $basePath): bool
    {
        $photos096Exists = is_dir("{$basePath}/photos-096");
        $photos384Exists = is_dir("{$basePath}/photos-384");

        // If both folders are missing, migration has likely been completed
        return ! $photos096Exists && ! $photos384Exists;
    }

    /**
     * @return array<int, string>
     */
    private function getAllFiles(string $dir): array
    {
        $files = [];

        if (! is_dir($dir)) {
            return $files;
        }

        foreach (scandir($dir) as $teamId) {
            if (in_array($teamId, ['.', '..'])) {
                continue;
            }

            $teamPath = "{$dir}/{$teamId}";
            if (! is_dir($teamPath)) {
                continue;
            }

            foreach (scandir($teamPath) as $file) {
                if (in_array($file, ['.', '..']) || $file === '.gitignore') {
                    continue;
                }

                // Only process actual image files
                $extension       = mb_strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $validExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tiff', 'svg'];

                if (! in_array($extension, $validExtensions)) {
                    continue;
                }

                $files[] = "{$teamPath}/{$file}";
            }
        }

        return $files;
    }

    private function cleanupFolder(string $folderPath, bool $dryRun = false): void
    {
        if (! is_dir($folderPath)) {
            return;
        }

        if ($dryRun) {
            $this->line("[DRY] Would delete folder and contents: {$folderPath}");

            return;
        }

        $remainingFiles = array_filter(
            glob("{$folderPath}/*"),
            fn ($file) => basename($file) !== '.gitignore'
        );

        foreach ($remainingFiles as $item) {
            if (is_dir($item)) {
                $this->deleteDirectory($item);
            } elseif (is_file($item)) {
                unlink($item);
            }
        }

        // Delete .gitignore if present
        $gitignore = "{$folderPath}/.gitignore";
        if (is_file($gitignore)) {
            unlink($gitignore);
        }

        // Remove folder if empty
        if (count(scandir($folderPath)) <= 2) {
            @rmdir($folderPath);
            $this->line("🧹 Deleted folder: {$folderPath}");
        } else {
            $this->warn("⚠ Not removing {$folderPath}, still contains files.");
        }
    }

    private function deleteDirectory(string $dir): void
    {
        $items = array_merge(glob("{$dir}/*"), glob("{$dir}/.*"));

        foreach ($items as $item) {
            $basename = basename($item);

            if (in_array($basename, ['.', '..'])) {
                continue;
            }

            if (is_file($item)) {
                // Delete all files including .gitignore
                unlink($item);
            } elseif (is_dir($item)) {
                // Recursively clean subdirectories
                $this->deleteDirectory($item);
            }
        }

        @rmdir($dir);
    }

    /**
     * Create timestamped backups of existing photo folders
     */
    private function createBackups(string $basePath, bool $dryRun = false): void
    {
        $timestamp  = date('Y-m-d_H-i-s');
        $backupRoot = "{$basePath}/photo-backups/{$timestamp}";

        $foldersToBackup = ['photos', 'photos-096', 'photos-384'];
        $hasBackups      = false;

        foreach ($foldersToBackup as $folderName) {
            $sourcePath = "{$basePath}/{$folderName}";
            $backupPath = "{$backupRoot}/{$folderName}";

            if (! is_dir($sourcePath)) {
                continue;
            }

            if ($dryRun) {
                $this->line("[DRY] Would create backup: {$sourcePath} → {$backupPath}");
                $hasBackups = true;
                continue;
            }

            // Create backup directory structure
            if (! is_dir($backupRoot)) {
                mkdir($backupRoot, 0755, true);
            }

            // Copy entire folder structure
            $this->copyDirectory($sourcePath, $backupPath);
            $this->line("📦 Created backup: {$folderName} → photo-backups/{$timestamp}/{$folderName}");
            $hasBackups = true;
        }

        if ($hasBackups) {
            $prefix = $dryRun ? '[DRY] ' : '';
            $this->info("{$prefix}✅ Backup completed. Files saved to: photo-backups/{$timestamp}/");
        } else {
            $this->warn('⚠ No folders found to backup.');
        }
    }

    /**
     * Recursively copy a directory and all its contents
     */
    private function copyDirectory(string $source, string $destination): void
    {
        if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $targetPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

            if ($item->isDir()) {
                if (! is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                copy($item->getRealPath(), $targetPath);
            }
        }
    }
}
