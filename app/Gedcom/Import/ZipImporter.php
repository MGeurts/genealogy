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

            // Extract all files
            $zip->extractTo($this->tempPath);
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
                $this->gedcomContent = file_get_contents($filePath);
                Log::info('GEDCOM file found in ZIP', ['file' => $file->getFilename()]);
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

        Log::info('ZIP extraction complete', [
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
