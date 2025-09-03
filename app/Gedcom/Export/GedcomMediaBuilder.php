<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// ==============================================================================
// GEDCOM MEDIA BUILDER - Handles media objects and files
// ==============================================================================

/**
 * GEDCOM Media Builder Class
 *
 * Specializes in handling media objects (primarily images) including:
 * - Media file collection and organization
 * - GEDCOM media object record creation
 * - File path management for ZIP exports
 * - Image metadata and title generation
 */
class GedcomMediaBuilder
{
    /** @var int Counter for generating media object IDs */
    private int $nextMediaId = 1;

    /** @var array<int, array> Media objects by person ID */
    private array $mediaObjects = [];

    /** @var array<string> Collection of all media files for ZIP export */
    private array $mediaFiles = [];

    /**
     * Create media builder instance.
     *
     * @param  string  $format  Export format to determine media handling
     * @param  GedcomFormatter  $formatter  Text formatter instance
     */
    public function __construct(
        private string $format,
        private GedcomFormatter $formatter
    ) {}

    // --------------------------------------------------------------------------------------
    // MEDIA COLLECTION METHODS
    // --------------------------------------------------------------------------------------

    /**
     * Collect all media objects for the individuals being exported.
     *
     * Scans through all individuals and gathers their associated media files,
     * preparing them for both GEDCOM record creation and ZIP export.
     *
     * @param  Collection<Person>  $individuals  Collection of Person models
     */
    public function collectMediaObjects(Collection $individuals): void
    {
        foreach ($individuals as $person) {
            $personMedia = $this->getPersonImages($person);

            if (! empty($personMedia)) {
                $this->mediaObjects[$person->id] = $personMedia;

                // Only collect file paths for ZIP export if format supports media
                if (in_array($this->format, ['zipmedia', 'gedzip'])) {
                    foreach ($personMedia as $media) {
                        $this->mediaFiles[] = $media['disk_path'];
                    }
                }
            }
        }

        Log::info('Collected media objects for ' . count($this->mediaObjects) . ' individuals, ' . count($this->mediaFiles) . ' files for ZIP export');
    }

    /**
     * Get collected media files for ZIP export.
     *
     * @return array<string> Array of media file paths
     */
    public function getMediaFiles(): array
    {
        return $this->mediaFiles;
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM RECORD BUILDING
    // --------------------------------------------------------------------------------------

    /**
     * Build media object references for an individual.
     *
     * Creates OBJE references linking individual records to their
     * associated media object records.
     *
     * @param  Person  $person  Person model instance
     * @return array<string> Media reference lines
     */
    public function buildIndividualMediaFields(Person $person): array
    {
        $lines = [];

        if (isset($this->mediaObjects[$person->id])) {
            foreach ($this->mediaObjects[$person->id] as $media) {
                $lines[] = "1 OBJE @M{$media['id']}@";
            }
        }

        return $lines;
    }

    /**
     * Build all media object records.
     *
     * Creates complete GEDCOM media object records for all collected
     * media files, following GEDCOM 7.0 media structure specifications.
     *
     * @return string All media records
     */
    public function buildMediaRecords(): string
    {
        $gedcom = '';

        foreach ($this->mediaObjects as $personId => $mediaList) {
            foreach ($mediaList as $media) {
                $gedcom .= $this->buildMediaRecord($media);
            }
        }

        return $gedcom;
    }

    /**
     * Get images for a specific person.
     *
     * Scans the person's photo directory and creates media object entries
     * for all original WebP files, filtering out resized variants.
     *
     * @param  Person  $person  Person model instance
     * @return array<array> Array of media objects
     */
    private function getPersonImages(Person $person): array
    {
        $directory = "{$person->team_id}/{$person->id}";

        if (! Storage::disk('photos')->exists($directory)) {
            return [];
        }

        $allFiles     = Storage::disk('photos')->files($directory);
        $mediaObjects = [];

        // Get only original .webp files (not _medium.webp or _small.webp)
        $images = collect($allFiles)
            ->filter(function ($file): bool {
                return $this->isOriginalWebpFile($file);
            })
            ->map(function ($originalFile): array {
                // Extract filename without extension for database comparison
                $filename           = basename($originalFile);
                $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);

                return [
                    'filename'  => $filenameWithoutExt,
                    'disk_path' => $originalFile, // For file system access
                    'url'       => Storage::disk('photos')->url($originalFile),
                ];
            })
            ->sortBy('filename')
            ->values();

        // Convert to media objects with GEDCOM IDs
        foreach ($images as $image) {
            $mediaId = $this->nextMediaId++;

            $mediaObjects[] = [
                'id'             => $mediaId,
                'filename'       => $image['filename'],
                'file_reference' => $image['filename'] . '.webp',
                'mime_type'      => 'image/webp',
                'disk_path'      => $image['disk_path'],
                'url'            => $image['url'],
                'title'          => $this->generateImageTitle($image['filename']),
            ];
        }

        return $mediaObjects;
    }

    /**
     * Check if file is an original .webp file (not a resized variant).
     *
     * Filters out thumbnail and medium-sized variants to include only
     * original full-resolution images in the export.
     *
     * @param  string  $file  File path
     * @return bool True if original .webp file
     */
    private function isOriginalWebpFile(string $file): bool
    {
        $filename = basename($file);

        return str_ends_with($filename, '.webp')
            && ! str_ends_with($filename, '_medium.webp')
            && ! str_ends_with($filename, '_small.webp');
    }

    /**
     * Generate a descriptive title for an image.
     *
     * Converts technical filenames into human-readable titles
     * suitable for GEDCOM media records.
     *
     * @param  string  $filename  Original filename
     * @return string Human-readable title
     */
    private function generateImageTitle(string $filename): string
    {
        // Convert filename to readable title
        $title = str_replace(['_', '-'], ' ', $filename);
        $title = ucwords($title);

        return $title;
    }

    /**
     * Build a single media object record.
     *
     * Creates a GEDCOM 7.0 compliant media object record with file
     * reference, format information, and descriptive title.
     *
     * @param  array  $media  Media object data
     * @return string Media GEDCOM record
     */
    private function buildMediaRecord(array $media): string
    {
        $lines   = [];
        $lines[] = "0 @M{$media['id']}@ OBJE";

        // File reference - GEDCOM 7.0 structure
        $lines[] = "1 FILE {$media['file_reference']}";
        $lines[] = "2 FORM {$media['mime_type']}";  // FORM now uses MIME type directly

        // Title at file level
        if (! empty($media['title'])) {
            $lines[] = "2 TITL {$this->formatter->sanitizeText($media['title'])}";
        }

        // Notes or additional metadata could be added here

        return implode($this->formatter->eol(), $lines) . $this->formatter->eol();
    }
}
