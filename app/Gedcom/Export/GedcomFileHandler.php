<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use Exception;
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
        $gedcomPath = $tempDir . '/' . $gedcomFile;
        $zipPath    = $tempDir . '/' . $this->basename . '.zip';

        try {
            $this->createGedcomFile($gedcomPath, $gedcom);

            if (in_array($this->format, ['zipmedia', 'gedzip'])) {
                $this->createZipWithMedia($zipPath, $gedcomPath, $gedcomFile, $mediaFiles);
            } else {
                $this->createZipFile($zipPath, $gedcomPath, $gedcomFile);
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
        $pattern = $tempDir . '/' . $this->basename . '.*';

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
        $zip    = new ZipArchive();
        $result = $zip->open($zipPath, ZipArchive::CREATE);

        if ($result !== true) {
            throw new RuntimeException('Failed to create ZIP archive: ' . $result);
        }

        // Add GEDCOM file
        if (! $zip->addFile($gedcomPath, $gedcomFile)) {
            $zip->close();
            throw new RuntimeException('Failed to add GEDCOM file to ZIP archive');
        }

        // Add media files
        $mediaDir = 'media/';
        foreach ($mediaFiles as $mediaPath) {
            $diskPath = Storage::disk('photos')->path($mediaPath);

            if (file_exists($diskPath)) {
                $filename    = basename($mediaPath);
                $archivePath = $mediaDir . $filename;

                if (! $zip->addFile($diskPath, $archivePath)) {
                    Log::warning("Failed to add media file to ZIP: {$mediaPath}");
                }
            } else {
                Log::warning("Media file not found: {$diskPath}");
            }
        }

        if (! $zip->close()) {
            throw new RuntimeException('Failed to close ZIP archive');
        }

        Log::info('Created ZIP with ' . count($mediaFiles) . ' media files');
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
        $zip    = new ZipArchive();
        $result = $zip->open($zipPath, ZipArchive::CREATE);

        if ($result !== true) {
            throw new RuntimeException('Failed to create ZIP archive: ' . $result);
        }

        if (! $zip->addFile($gedcomPath, $gedcomFile)) {
            $zip->close();
            throw new RuntimeException('Failed to add GEDCOM file to ZIP archive');
        }

        if (! $zip->close()) {
            throw new RuntimeException('Failed to close ZIP archive');
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
        if (! is_dir($tempDir) && ! mkdir($tempDir, 0755, true)) {
            throw new RuntimeException('Unable to create temp directory: ' . $tempDir);
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
        if (file_put_contents($path, $gedcom) === false) {
            throw new RuntimeException('Failed to write GEDCOM file: ' . $path);
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
                    throw new RuntimeException('Cannot open ZIP file for reading');
                }

                while (! feof($handle)) {
                    echo fread($handle, self::STREAM_BUFFER_SIZE);
                    flush();
                }
                fclose($handle);
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
