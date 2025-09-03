<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

// ==============================================================================
// GEDCOM FILE HANDLER - Handles file operations and downloads
// ==============================================================================

/**
 * GEDCOM File Handler Class
 *
 * Manages all file system operations for GEDCOM export including:
 * - Direct GEDCOM file downloads
 * - ZIP archive creation with optional media files
 * - Temporary file management and cleanup
 * - Streaming downloads for large files
 */
class GedcomFileHandler
{
    // --------------------------------------------------------------------------------------
    // CONSTANTS
    // --------------------------------------------------------------------------------------

    /** @var int Default buffer size for file streaming */
    private const STREAM_BUFFER_SIZE = 8192;

    /**
     * Create file handler instance.
     *
     * @param  string  $basename  Base filename without extension
     * @param  string  $format  Export format
     * @param  string  $filename  Complete filename with extension
     */
    public function __construct(
        private string $basename,
        private string $format,
        private string $filename
    ) {}

    // --------------------------------------------------------------------------------------
    // DOWNLOAD METHODS
    // --------------------------------------------------------------------------------------

    /**
     * Download GEDCOM content as a direct file.
     *
     * Streams GEDCOM content directly to the browser with appropriate
     * headers for genealogy software compatibility.
     *
     * @param  string  $gedcom  GEDCOM content to download
     * @return StreamedResponse Laravel streamed response
     */
    public function downloadGedcom(string $gedcom): StreamedResponse
    {
        return Response::streamDownload(
            fn () => print ($gedcom),
            $this->filename,
            $this->getGedcomHeaders()
        );
    }

    /**
     * Download GEDCOM content as a ZIP archive with optional media files.
     *
     * Creates a ZIP archive containing the GEDCOM file and optionally
     * includes media files based on the export format.
     *
     * @param  string  $gedcom  GEDCOM content to archive
     * @param  array<string>  $mediaFiles  Media files to include
     * @return StreamedResponse Laravel streamed response
     *
     * @throws RuntimeException When ZIP creation fails
     */
    public function downloadZip(string $gedcom, array $mediaFiles = []): StreamedResponse
    {
        $tempDir    = $this->ensureTempDirectory();
        $gedcomFile = $this->basename . '.ged';
        $gedcomPath = $tempDir . DIRECTORY_SEPARATOR . $gedcomFile;
        $zipPath    = $tempDir . DIRECTORY_SEPARATOR . $this->basename . '.zip';

        try {
            $this->createGedcomFile($gedcomPath, $gedcom);

            if (in_array($this->format, ['zipmedia', 'gedzip'])) {
                $this->createZipWithMedia($zipPath, $gedcomPath, $gedcomFile, $mediaFiles);
            } else {
                $this->createZipFile($zipPath, $gedcomPath, $gedcomFile);
            }

            // Verify ZIP file was created successfully
            if (! file_exists($zipPath)) {
                throw new RuntimeException('ZIP file was not created: ' . $zipPath);
            }

            // Verify ZIP file is readable
            if (! is_readable($zipPath)) {
                throw new RuntimeException('ZIP file is not readable: ' . $zipPath);
            }

            // Verify ZIP file has content
            if (filesize($zipPath) === 0) {
                throw new RuntimeException('ZIP file is empty: ' . $zipPath);
            }

            return $this->streamZipDownload($zipPath, $gedcomPath);
        } catch (Exception $e) {
            $this->cleanupFiles([$gedcomPath, $zipPath]);
            throw new RuntimeException('Failed to create ZIP file: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Clean up temporary files and patterns.
     *
     * Removes temporary files created during export process,
     * including pattern-based cleanup for all related files.
     */
    public function cleanup(): void
    {
        $tempDir = Storage::path('temp');
        $pattern = $tempDir . DIRECTORY_SEPARATOR . $this->basename . '.*';

        foreach (glob($pattern) as $file) {
            @unlink($file);
        }
    }

    // --------------------------------------------------------------------------------------
    // ZIP CREATION METHODS
    // --------------------------------------------------------------------------------------

    /**
     * Create ZIP archive with media files.
     *
     * Builds a comprehensive ZIP archive containing the GEDCOM file
     * and all associated media files organized in a media directory.
     *
     * @param  string  $zipPath  ZIP file path
     * @param  string  $gedcomPath  GEDCOM file path
     * @param  string  $gedcomFile  GEDCOM filename for archive
     * @param  array<string>  $mediaFiles  Media files to include
     *
     * @throws RuntimeException When ZIP creation fails
     */
    private function createZipWithMedia(string $zipPath, string $gedcomPath, string $gedcomFile, array $mediaFiles): void
    {
        $zip = new ZipArchive();

        // Delete existing file if it exists
        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        $result = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($result !== true) {
            throw new RuntimeException("Failed to create ZIP archive: {$this->getZipErrorMessage($result)} (Code: {$result})");
        }

        // Verify GEDCOM file exists before adding
        if (! file_exists($gedcomPath)) {
            $zip->close();
            throw new RuntimeException('GEDCOM file does not exist: ' . $gedcomPath);
        }

        // Add GEDCOM file
        if (! $zip->addFile($gedcomPath, $gedcomFile)) {
            $zip->close();
            throw new RuntimeException('Failed to add GEDCOM file to ZIP archive: ' . $gedcomPath);
        }

        // Add media files
        $mediaDir   = 'media/';
        $addedFiles = 0;
        foreach ($mediaFiles as $mediaPath) {
            $diskPath = Storage::disk('photos')->path($mediaPath);

            if (file_exists($diskPath) && is_readable($diskPath)) {
                $filename    = basename($mediaPath);
                $archivePath = $mediaDir . $filename;

                if ($zip->addFile($diskPath, $archivePath)) {
                    $addedFiles++;
                } else {
                    Log::warning("Failed to add media file to ZIP: {$mediaPath}");
                }
            } else {
                Log::warning("Media file not found or not readable: {$diskPath}");
            }
        }

        if (! $zip->close()) {
            throw new RuntimeException('Failed to close ZIP archive: ' . $zip->getStatusString());
        }

        // Verify the ZIP file was created and has content
        if (! file_exists($zipPath)) {
            throw new RuntimeException('ZIP file was not created after close(): ' . $zipPath);
        }

        if (filesize($zipPath) === 0) {
            throw new RuntimeException('ZIP file is empty after creation: ' . $zipPath);
        }

        Log::info("Created ZIP with GEDCOM file and {$addedFiles} media files");
    }

    /**
     * Create simple ZIP archive with GEDCOM file only.
     *
     * @param  string  $zipPath  ZIP file path
     * @param  string  $gedcomPath  GEDCOM file path
     * @param  string  $gedcomFile  GEDCOM filename for archive
     *
     * @throws RuntimeException When ZIP creation fails
     */
    private function createZipFile(string $zipPath, string $gedcomPath, string $gedcomFile): void
    {
        $zip = new ZipArchive();

        // Delete existing file if it exists
        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        $result = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($result !== true) {
            throw new RuntimeException("Failed to create ZIP archive: {$this->getZipErrorMessage($result)} (Code: {$result})");
        }

        // Verify GEDCOM file exists before adding
        if (! file_exists($gedcomPath)) {
            $zip->close();
            throw new RuntimeException('GEDCOM file does not exist: ' . $gedcomPath);
        }

        if (! $zip->addFile($gedcomPath, $gedcomFile)) {
            $zip->close();
            throw new RuntimeException('Failed to add GEDCOM file to ZIP archive: ' . $gedcomPath);
        }

        if (! $zip->close()) {
            throw new RuntimeException('Failed to close ZIP archive: ' . $zip->getStatusString());
        }

        // Verify the ZIP file was created
        if (! file_exists($zipPath)) {
            throw new RuntimeException('ZIP file was not created after close(): ' . $zipPath);
        }

        if (filesize($zipPath) === 0) {
            throw new RuntimeException('ZIP file is empty after creation: ' . $zipPath);
        }
    }

    // --------------------------------------------------------------------------------------
    // FILE SYSTEM OPERATIONS
    // --------------------------------------------------------------------------------------

    /**
     * Ensure temp directory exists.
     *
     * @return string Temp directory path
     *
     * @throws RuntimeException When directory creation fails
     */
    private function ensureTempDirectory(): string
    {
        $tempDir = Storage::path('temp');

        if (! is_dir($tempDir)) {
            if (! mkdir($tempDir, 0755, true)) {
                throw new RuntimeException('Unable to create temp directory: ' . $tempDir);
            }
        }

        // Verify directory is writable
        if (! is_writable($tempDir)) {
            throw new RuntimeException('Temp directory is not writable: ' . $tempDir);
        }

        return $tempDir;
    }

    /**
     * Create GEDCOM file on disk.
     *
     * @param  string  $path  File path
     * @param  string  $gedcom  GEDCOM content
     *
     * @throws RuntimeException When file creation fails
     */
    private function createGedcomFile(string $path, string $gedcom): void
    {
        $result = file_put_contents($path, $gedcom);
        if ($result === false) {
            throw new RuntimeException('Failed to write GEDCOM file: ' . $path);
        }

        // Verify file was created with content
        if (! file_exists($path)) {
            throw new RuntimeException('GEDCOM file was not created: ' . $path);
        }

        if (filesize($path) === 0) {
            throw new RuntimeException('GEDCOM file is empty: ' . $path);
        }
    }

    /**
     * Stream ZIP file download with cleanup.
     *
     * @param  string  $zipPath  ZIP file path
     * @param  string  $gedcomPath  GEDCOM file path for cleanup
     * @return StreamedResponse Laravel streamed response
     */
    private function streamZipDownload(string $zipPath, string $gedcomPath): StreamedResponse
    {
        // Schedule cleanup after download
        register_shutdown_function(function () use ($zipPath, $gedcomPath): void {
            $this->cleanupFiles([$zipPath, $gedcomPath]);
        });

        return response()->streamDownload(
            function () use ($zipPath): void {
                $handle = fopen($zipPath, 'rb');
                if ($handle === false) {
                    throw new RuntimeException('Cannot open ZIP file for reading: ' . $zipPath);
                }

                try {
                    while (! feof($handle)) {
                        $data = fread($handle, self::STREAM_BUFFER_SIZE);
                        if ($data === false) {
                            throw new RuntimeException('Error reading from ZIP file');
                        }
                        echo $data;
                        flush();
                    }
                } finally {
                    fclose($handle);
                }
            },
            $this->filename,
            [
                'Content-Type'        => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $this->filename . '"',
            ]
        );
    }

    /**
     * Clean up specific temporary files.
     *
     * @param  array<string>  $paths  File paths to delete
     */
    private function cleanupFiles(array $paths): void
    {
        foreach ($paths as $path) {
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    // --------------------------------------------------------------------------------------
    // HELPER METHODS
    // --------------------------------------------------------------------------------------

    /**
     * Get human-readable error message for ZipArchive error codes.
     *
     * @param  int  $code  ZipArchive error code
     * @return string Error message
     */
    private function getZipErrorMessage(int $code): string
    {
        return match ($code) {
            ZipArchive::ER_OK          => 'No error',
            ZipArchive::ER_MULTIDISK   => 'Multi-disk zip archives not supported',
            ZipArchive::ER_RENAME      => 'Renaming temporary file failed',
            ZipArchive::ER_CLOSE       => 'Closing zip archive failed',
            ZipArchive::ER_SEEK        => 'Seek error',
            ZipArchive::ER_READ        => 'Read error',
            ZipArchive::ER_WRITE       => 'Write error',
            ZipArchive::ER_CRC         => 'CRC error',
            ZipArchive::ER_ZIPCLOSED   => 'Containing zip archive was closed',
            ZipArchive::ER_NOENT       => 'No such file',
            ZipArchive::ER_EXISTS      => 'File already exists',
            ZipArchive::ER_OPEN        => 'Can\'t open file',
            ZipArchive::ER_TMPOPEN     => 'Failure to create temporary file',
            ZipArchive::ER_ZLIB        => 'Zlib error',
            ZipArchive::ER_MEMORY      => 'Memory allocation failure',
            ZipArchive::ER_CHANGED     => 'Entry has been changed',
            ZipArchive::ER_COMPNOTSUPP => 'Compression method not supported',
            ZipArchive::ER_EOF         => 'Premature EOF',
            ZipArchive::ER_INVAL       => 'Invalid argument',
            ZipArchive::ER_NOZIP       => 'Not a zip archive',
            ZipArchive::ER_INTERNAL    => 'Internal error',
            ZipArchive::ER_INCONS      => 'Zip archive inconsistent',
            ZipArchive::ER_REMOVE      => 'Can\'t remove file',
            ZipArchive::ER_DELETED     => 'Entry has been deleted',
            default                    => 'Unknown error',
        };
    }

    // --------------------------------------------------------------------------------------
    // HEADER CONFIGURATION
    // --------------------------------------------------------------------------------------

    /**
     * Get HTTP headers for GEDCOM download.
     *
     * @return array<string, string> HTTP headers
     */
    private function getGedcomHeaders(): array
    {
        return [
            'Content-Type'        => 'text/plain; charset=UTF8',
            'Content-Disposition' => 'attachment; filename="' . $this->filename . '"',
        ];
    }
}
