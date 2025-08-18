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
     * into a single unified photos folder.
     *
     * Usage: php artisan photos:migrate [--dry-run]
     *
     * OLD STRUCTURE:
     * storage/app/public/
     * ├── photos/          (original size)
     *     └── {teamId}/
     *         ├── {personId}_{index}_{timestamp}.{ext}
     * ├── photos-096/      (small size)
     *     └── {teamId}/
     *         ├── {personId}_{index}_{timestamp}.{ext}
     * └── photos-384/      (medium size)
     *     └── {teamId}/
     *         ├── {personId}_{index}_{timestamp}.{ext}
     *
     * NEW STRUCTURE:
     * storage/app/public/photos/
     * └── {teamId}/
     *     └── {personId}/
     *         ├── {personId}_{index}_{timestamp}.{ext}         (original size)
     *         ├── {personId}_{index}_{timestamp}_small.{ext}   (small size)
     *         └── {personId}_{index}_{timestamp}_medium.{ext}  (medium size)
     *
     * OPERATIONS:
     * 1. Creates backup of existing folders (photos, photos-096, photos-384) with timestamp
     * 2. Checks if migration has already been run (photos-096 and photos-384 must exist)
     * 3. Scans each old folder and processes files within teamId/filename structure
     * 4. Extracts team ID from directory and person ID from filename (before first underscore)
     * 5. Renames files by adding size suffixes (_small, _medium) for non-original images
     * 6. Creates new directory structure (photos/{teamId}/{personId}/)
     * 7. Copies files to new locations and deletes originals
     * 8. Cleans up empty folders (except main photos folder)
     *
     * SAFETY FEATURES:
     * - Creates timestamped backups before migration
     * - Prevents multiple runs by checking if old folders still exist
     * - Dry-run mode (--dry-run) shows actions without executing
     * - Skips .gitignore files during processing
     * - Error handling for unexpected file structures
     * - Ensures target directories exist before copying
     *
     * EXAMPLE:
     * photos-096/1/560_001_20250816113838.jpg → photos/1/560/560_001_20250816113838_small.jpg
     * photos-384/1/560_001_20250816113838.png → photos/1/560/560_001_20250816113838_medium.png
     *
     * PURPOSE:
     * Refactors photo storage system for better organization by team and person while
     * maintaining different image sizes in a structured way for easier management.
     * Supports all common image formats (JPG, PNG, WebP, GIF, BMP, TIFF, SVG).
     * This improves performance and allow migrating disks to other storage solutions.
     *
     * WARNING:
     * This command is intended to be run ONLY ONCE during the migration process!!
     */
    protected $signature = 'photos:migrate {--dry-run : Only show actions without moving or deleting files and/or folders}';

    protected $description = 'Migrate old photo folder structure (photos, photos-096, photos-384) into the new unified structure (photos)';

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

        // Old folders → size key
        $oldFolders = [
            'photos'     => 'original',
            'photos-096' => 'small',
            'photos-384' => 'medium',
        ];

        $newRoot = "{$basePath}/photos";

        foreach ($oldFolders as $folderName => $size) {
            $sourceRoot = "{$basePath}/{$folderName}";

            if (! is_dir($sourceRoot)) {
                $this->warn("⚠ Folder {$sourceRoot} not found, skipping...");
                continue;
            }

            $this->info("Scanning {$folderName} for {$size} photos...");

            foreach ($this->getAllFiles($sourceRoot) as $oldPath) {
                if (str_contains($oldPath, '.gitignore')) {
                    $this->warn("Skipping .gitignore file: {$oldPath}");
                    continue;
                }

                $relativePath = Str::after($oldPath, "{$sourceRoot}/");
                $parts        = explode('/', $relativePath);

                if (count($parts) < 2) {
                    $this->warn("⚠ Unexpected file structure: {$relativePath}");
                    continue;
                }

                $teamId   = $parts[0];
                $filename = $parts[1];
                $personId = Str::before($filename, '_');

                if ($size !== 'original') {
                    // Extract file extension and add size suffix before the extension
                    $extension            = pathinfo($filename, PATHINFO_EXTENSION);
                    $nameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
                    $filename             = "{$nameWithoutExtension}_{$size}.{$extension}";
                }

                $newPath = "{$newRoot}/{$teamId}/{$personId}/{$filename}";

                if ($this->option('dry-run')) {
                    $this->line("[DRY] {$oldPath} → {$newPath}");
                } else {
                    $newDir = dirname($newPath);
                    if (! is_dir($newDir)) {
                        mkdir($newDir, 0755, true);
                    }

                    copy($oldPath, $newPath);
                    unlink($oldPath);

                    $this->line("✔ {$oldPath} → {$newPath}");
                }
            }

            // Cleanup old folder
            if ($folderName !== 'photos') {
                $this->cleanupFolder($sourceRoot, $this->option('dry-run'));
            }
        }

        $this->info($this->option('dry-run') ? '✅ Photo migration DRY RUN completed successfully.' : '✅ Photo migration completed successfully.');

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

    private function getAllFiles(string $dir): array
    {
        $files = [];

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
