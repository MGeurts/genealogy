<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

use App\Models\Person;
use App\PersonPhotos;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Handles importing media files referenced in GEDCOM to person records
 */
class MediaImportHandler
{
    private array $mediaFiles;

    private array $mediaObjects = [];

    private array $personMediaMap = [];

    /**
     * @param  array  $mediaFiles  Array of basename => filepath mappings
     */
    public function __construct(array $mediaFiles = [])
    {
        $this->mediaFiles = $mediaFiles;
    }

    /**
     * Parse GEDCOM data to build media object registry
     * Call this BEFORE processing individuals to build the media lookup table
     *
     * @param  array  $gedcomData  The full parsed GEDCOM data
     */
    public function buildMediaRegistry(array $gedcomData): void
    {
        // Look for OBJE records in the gedcomData array
        // These are the media object definitions

        // Since we don't have direct access to level 0 OBJE records in the current
        // parser structure, we'll need to parse them from individuals
        // The parser should be updated to extract these, but for now we'll work around it

        Log::debug('Media registry built', [
            'media_objects_count' => count($this->mediaObjects),
        ]);
    }

    /**
     * Parse raw GEDCOM content to extract media object definitions
     * This is a workaround to extract @M#@ OBJE definitions
     *
     * @param  string  $gedcomContent  Raw GEDCOM file content
     */
    public function parseMediaObjects(string $gedcomContent): void
    {
        $lines         = explode("\n", str_replace(["\r\n", "\r"], "\n", $gedcomContent));
        $currentObject = null;

        foreach ($lines as $line) {
            $line = mb_trim($line);
            if (empty($line)) {
                continue;
            }

            $parts = explode(' ', $line, 4);
            if (count($parts) < 2) {
                continue;
            }

            $level = (int) $parts[0];

            // Level 0 record
            if ($level === 0 && count($parts) >= 3) {
                // Save previous object before starting new one
                if ($currentObject !== null && $currentObject['file']) {
                    $this->mediaObjects[$currentObject['id']] = $currentObject;
                    Log::debug('Media object registered', [
                        'id'   => $currentObject['id'],
                        'file' => $currentObject['file'],
                    ]);
                }

                $id  = mb_trim($parts[1], '@');
                $tag = $parts[2];

                if ($tag === 'OBJE') {
                    $currentObject = [
                        'id'     => $id,
                        'file'   => null,
                        'format' => null,
                        'title'  => null,
                    ];
                } else {
                    $currentObject = null;
                }
            }
            // Level 1 fields for current OBJE
            elseif ($level === 1 && $currentObject !== null) {
                $tag   = $parts[1];
                $value = isset($parts[2]) ? implode(' ', array_slice($parts, 2)) : '';

                if ($tag === 'FILE') {
                    $currentObject['file'] = $this->normalizeFilename($value);
                } elseif ($tag === 'FORM') {
                    $currentObject['format'] = $value;
                } elseif ($tag === 'TITL') {
                    $currentObject['title'] = $value;
                }
            }
            // Level 2 FORM/TITL under FILE
            elseif ($level === 2 && $currentObject !== null) {
                $tag   = $parts[1];
                $value = isset($parts[2]) ? implode(' ', array_slice($parts, 2)) : '';

                if ($tag === 'FORM') {
                    $currentObject['format'] = $value;
                } elseif ($tag === 'TITL') {
                    $currentObject['title'] = $value;
                }
            }
        }

        // Don't forget the last object
        if ($currentObject !== null && $currentObject['file']) {
            $this->mediaObjects[$currentObject['id']] = $currentObject;
            Log::debug('Media object registered (last)', [
                'id'   => $currentObject['id'],
                'file' => $currentObject['file'],
            ]);
        }

        Log::info('Media objects parsed', [
            'count'   => count($this->mediaObjects),
            'objects' => array_keys($this->mediaObjects),
        ]);
    }

    /**
     * Parse GEDCOM individual data to extract media references
     * Call this during individual processing to build media mapping
     *
     * @param  string  $gedcomId  The GEDCOM ID of the person
     * @param  array  $individual  The parsed individual data
     */
    public function extractMediaReferences(string $gedcomId, array $individual): void
    {
        if (! isset($individual['data'])) {
            return;
        }

        $mediaRefs = [];

        foreach ($individual['data'] as $field) {
            // Handle OBJE tags - these reference media objects
            if ($field['tag'] === 'OBJE') {
                // The value is the reference ID (e.g., @M1@)
                $mediaRefId = mb_trim($field['value'] ?? '', '@');

                if ($mediaRefId && isset($this->mediaObjects[$mediaRefId])) {
                    $filename = $this->mediaObjects[$mediaRefId]['file'];
                    if ($filename) {
                        $mediaRefs[] = $filename;
                        Log::debug('Media reference found', [
                            'person'    => $gedcomId,
                            'media_ref' => $mediaRefId,
                            'filename'  => $filename,
                        ]);
                    }
                }
            }
        }

        if (! empty($mediaRefs)) {
            $this->personMediaMap[$gedcomId] = $mediaRefs;

            Log::debug('Media references collected for person', [
                'gedcom_id'   => $gedcomId,
                'media_count' => count($mediaRefs),
                'files'       => $mediaRefs,
            ]);
        }
    }

    /**
     * Import media files to persons after all persons are created
     *
     * @param  array  $personMap  Mapping of GEDCOM ID to Person database ID
     */
    public function importMediaToPersons(array $personMap): array
    {
        $stats = [
            'processed'     => 0,
            'succeeded'     => 0,
            'failed'        => 0,
            'missing_files' => 0,
            'total_photos'  => 0,
        ];

        foreach ($this->personMediaMap as $gedcomId => $mediaRefs) {
            if (! isset($personMap[$gedcomId])) {
                Log::warning('Person not found for media import', [
                    'gedcom_id' => $gedcomId,
                ]);
                continue;
            }

            $personId = $personMap[$gedcomId];
            $person   = Person::find($personId);

            if (! $person) {
                Log::warning('Person model not found for media import', [
                    'person_id' => $personId,
                ]);
                continue;
            }

            $stats['processed']++;

            $photos = $this->collectPhotosForPerson($mediaRefs);

            if (empty($photos)) {
                $stats['missing_files']++;
                Log::info('No photo files found for person', [
                    'person_id' => $personId,
                    'gedcom_id' => $gedcomId,
                    'refs'      => $mediaRefs,
                ]);
                continue;
            }

            try {
                $personPhotos = new PersonPhotos($person);
                $savedCount   = $personPhotos->save($photos);

                if ($savedCount > 0) {
                    $stats['succeeded']++;
                    $stats['total_photos'] += $savedCount;
                    Log::info('Photos imported for person', [
                        'person_id'    => $personId,
                        'person_name'  => $person->name,
                        'photos_count' => $savedCount,
                    ]);
                } else {
                    $stats['failed']++;
                }
            } catch (Exception $e) {
                $stats['failed']++;
                Log::error('Failed to import photos for person', [
                    'person_id'   => $personId,
                    'person_name' => $person->name,
                    'error'       => $e->getMessage(),
                    'trace'       => $e->getTraceAsString(),
                ]);
            }
        }

        return $stats;
    }

    /**
     * Get the person-media mapping for debugging
     */
    public function getPersonMediaMap(): array
    {
        return $this->personMediaMap;
    }

    /**
     * Get the media objects registry for debugging
     */
    public function getMediaObjects(): array
    {
        return $this->mediaObjects;
    }

    /**
     * Collect actual photo file paths for a person's media references
     *
     * @param  array  $mediaRefs  Array of filename references
     * @return array Array of file paths that exist
     */
    private function collectPhotosForPerson(array $mediaRefs): array
    {
        $photos = [];

        foreach ($mediaRefs as $ref) {
            // Try exact match first
            if (isset($this->mediaFiles[$ref])) {
                $photos[] = $this->mediaFiles[$ref];
                continue;
            }

            // Try case-insensitive match
            $refLower = mb_strtolower($ref);
            foreach ($this->mediaFiles as $basename => $filepath) {
                if (mb_strtolower($basename) === $refLower) {
                    $photos[] = $filepath;
                    break;
                }
            }
        }

        return $photos;
    }

    /**
     * Normalize filename from GEDCOM reference
     * Removes path separators and gets basename
     */
    private function normalizeFilename(string $filename): string
    {
        // Remove any path components
        $filename = basename($filename);

        // Handle Windows paths
        if (str_contains($filename, '\\')) {
            $parts    = explode('\\', $filename);
            $filename = end($parts);
        }

        // Handle forward slash paths
        if (str_contains($filename, '/')) {
            $parts    = explode('/', $filename);
            $filename = end($parts);
        }

        return mb_trim($filename);
    }
}
