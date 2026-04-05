<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

use Exception;
use Illuminate\Support\Facades\Log;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

/**
 * ZIP file handler for GEDCOM imports with media files
 */
class ZipImporter
{
    private string $tempPath;

    private ?string $gedcomContent = null;

    /** @var array<string, string> */
    private array $mediaFiles = [];

    public function __construct()
    {
        $this->tempPath = storage_path('app/temp/gedcom-import-' . uniqid());
    }

    /**
     * Destructor - ensure cleanup
     */
    public function __destruct()
    {
        $this->cleanup();
    }

    /**
     * Extract and process ZIP file
     *
     * @param  string  $zipPath  Path to the ZIP file
     * @return bool Success status
     *
     * @throws Exception
     */
    public function extract(string $zipPath): bool
    {
        if (! file_exists($zipPath)) {
            throw new Exception("ZIP file not found: {$zipPath}");
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new Exception("Failed to open ZIP file: {$zipPath}");
        }

        try {
            // Create temp directory
            if (! is_dir($this->tempPath)) {
                mkdir($this->tempPath, 0755, true);
            }

            // Resolve the canonical temp path once so every entry can be checked against it.
            // realpath() requires the directory to already exist, which it does at this point.
            $canonicalTempPath = realpath($this->tempPath);

            if ($canonicalTempPath === false) {
                throw new Exception('Could not resolve temporary extraction path.');
            }

            // Extract entries one-by-one and validate each destination path before writing.
            // ZipArchive::extractTo() is intentionally avoided here because it blindly
            // follows path components inside entry names (e.g. "../../public/shell.php"),
            // which allows a malicious archive to write files anywhere on the filesystem
            // — a vulnerability known as Zip Slip (CWE-22 / path traversal).
            $count = $zip->count();

            for ($i = 0; $i < $count; $i++) {
                $entryName = $zip->getNameIndex($i);

                if ($entryName === false) {
                    continue;
                }

                // Build the destination path and resolve it to its canonical form.
                // realpath() on a non-existent path returns false, so we resolve the
                // parent directory and append the sanitised filename instead.
                $entryBasename = basename($entryName);

                // Skip macOS resource-fork entries and other hidden artefacts.
                if ($entryBasename === '' || str_starts_with($entryBasename, '.')) {
                    continue;
                }

                // We intentionally flatten the archive: all files land directly in
                // tempPath regardless of any subdirectory structure inside the ZIP.
                // This is safe and sufficient — GEDCOM files and their media do not
                // rely on relative subdirectory paths at import time.
                $destination = $canonicalTempPath . DIRECTORY_SEPARATOR . $entryBasename;

                // Final guard: after joining, confirm the resolved destination is still
                // inside tempPath. str_starts_with on the canonicalised path is safe
                // because realpath() has already collapsed any ".." components.
                $resolvedDestination = realpath(dirname($destination));

                if ($resolvedDestination === false || ! str_starts_with($resolvedDestination . DIRECTORY_SEPARATOR, $canonicalTempPath . DIRECTORY_SEPARATOR)) {
                    Log::warning('GEDCOM ZIP: Skipping entry with path traversal attempt', [
                        'entry' => $entryName,
                    ]);
                    continue;
                }

                // Skip directories — we only need files.
                if (str_ends_with($entryName, '/')) {
                    continue;
                }

                $content = $zip->getFromIndex($i);

                if ($content === false) {
                    Log::warning('GEDCOM ZIP: Could not read entry', ['entry' => $entryName]);
                    continue;
                }

                file_put_contents($destination, $content);
            }

            $zip->close();

            // Find GEDCOM file and media files
            $this->processExtractedFiles();

            return true;
        } catch (Exception $e) {
            $zip->close();
            $this->cleanup();
            throw $e;
        }
    }

    /**
     * Get the extracted GEDCOM content
     */
    public function getGedcomContent(): ?string
    {
        return $this->gedcomContent;
    }

    /**
     * Get media files mapping (GEDCOM reference => actual file path)
     *
     * @return array<string, string>
     */
    public function getMediaFiles(): array
    {
        return $this->mediaFiles;
    }

    /**
     * Cleanup temporary files
     */
    public function cleanup(): void
    {
        if (is_dir($this->tempPath)) {
            $this->deleteDirectory($this->tempPath);
        }
    }

    /**
     * Process extracted files to identify GEDCOM and media
     */
    private function processExtractedFiles(): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->tempPath)
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                continue;
            }

            $filePath  = $file->getPathname();
            $extension = mb_strtolower($file->getExtension());

            // Find GEDCOM file
            if (in_array($extension, ['ged', 'gedcom'])) {
                $content = file_get_contents($filePath);

                if ($content === false) {
                    throw new Exception("Failed to read GEDCOM file: {$filePath}");
                }

                $this->gedcomContent = $content;
                Log::debug('GEDCOM file found in ZIP', ['file' => $file->getFilename()]);
            }

            // Find media files (images)
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff'])) {
                // Store with basename as key for matching with GEDCOM references
                $basename                    = $file->getBasename();
                $this->mediaFiles[$basename] = $filePath;

                Log::debug('Media file found in ZIP', [
                    'basename' => $basename,
                    'path'     => $filePath,
                ]);
            }
        }

        if ($this->gedcomContent === null) {
            throw new Exception('No GEDCOM file found in ZIP archive');
        }

        Log::debug('ZIP extraction complete', [
            'media_files_count' => count($this->mediaFiles),
            'gedcom_size'       => mb_strlen($this->gedcomContent),
        ]);
    }

    /**
     * Recursively delete directory
     */
    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $items = array_diff(scandir($dir), ['.', '..']);

        foreach ($items as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }
}
