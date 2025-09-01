<?php

declare(strict_types=1);

namespace App\Php\Gedcom;

use App\Models\Person;
use App\Models\User;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Log;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

/**
 * GEDCOM Export Class
 *
 * Handles the export of genealogical data to GEDCOM format with support for:
 * - Multiple output formats (GEDCOM, ZIP, GEDZIP)
 * - Extensible record building system
 * - Media objects (images) for individuals
 *
 * $export = new Export('my_family_tree', 'gedcom', 'My Family Name');
 * $gedcom = $export->export($individuals, $couples);
 * return $export->downloadGedcom($gedcom);
 */
class Export
{
    // --------------------------------------------------------------------------------------
    // CONSTANTS - Configuration and Standards
    // --------------------------------------------------------------------------------------

    /** @var string Current GEDCOM version being used */
    private const GEDCOM_VERSION = '7.0.16';

    /** @var int Default buffer size for file streaming */
    private const STREAM_BUFFER_SIZE = 8192;

    // --------------------------------------------------------------------------------------
    // PROPERTIES
    // --------------------------------------------------------------------------------------

    /** @var string Final filename with extension */
    private readonly string $filename;

    /** @var string File extension based on format */
    private readonly string $extension;

    /** @var array<string, int> Mapping of parent combinations to family IDs */
    private array $parentFamilyMapping = [];

    /** @var int Counter for generating media object IDs */
    private int $nextMediaId = 1;

    /** @var array<int, array> Media objects by person ID */
    private array $mediaObjects = [];

    /** @var array<string> Collection of all media files for ZIP export */
    private array $mediaFiles = [];

    // --------------------------------------------------------------------------------------
    // CONSTRUCTOR & VALIDATION
    // --------------------------------------------------------------------------------------

    /**
     * Create a new GEDCOM export instance.
     *
     * @param  string  $basename  Base filename (without extension)
     * @param  string  $format  Export format (gedcom|zip|zipmedia|gedzip)
     * @param  string  $teamname  Team Name
     */
    public function __construct(
        private string $basename,
        private readonly string $format,
        private readonly string $teamname,
    ) {
        $this->extension = $this->getExtension($format);
        $this->filename  = $this->basename . $this->extension;
    }

    /**
     * Clean up temporary files on destruction.
     */
    public function __destruct()
    {
        $tempDir = Storage::path('temp');
        $pattern = $tempDir . '/' . $this->basename . '.*';

        foreach (glob($pattern) as $file) {
            @unlink($file);
        }
    }

    // --------------------------------------------------------------------------------------
    // PUBLIC API - Main Export Methods
    // --------------------------------------------------------------------------------------

    /**
     * Export genealogical data to GEDCOM format.
     *
     * This is the main entry point for generating GEDCOM content.
     * Override individual build methods to customize specific record types.
     *
     * @param  Collection<Person>  $individuals  Collection of Person models
     * @param  Collection  $couples  Collection of couple models
     * @return string Complete GEDCOM content
     *
     * @throws InvalidArgumentException When data is empty
     */
    public function export(Collection $individuals, Collection $couples): string
    {
        if ($individuals->isEmpty() && $couples->isEmpty()) {
            throw new InvalidArgumentException('Cannot export empty genealogy data');
        }

        // Collect media objects for individuals
        $this->collectMediaObjects($individuals);

        return $this->buildGedcom($individuals, $couples);
    }

    /**
     * Download GEDCOM content as a direct file.
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
     * @param  string  $gedcom  GEDCOM content to archive
     * @return StreamedResponse Laravel streamed response
     *
     * @throws RuntimeException When ZIP creation fails
     */
    public function downloadZip(string $gedcom): StreamedResponse
    {
        $tempDir    = $this->ensureTempDirectory();
        $gedcomFile = $this->basename . '.ged';
        $gedcomPath = $tempDir . '/' . $gedcomFile;
        $zipPath    = $tempDir . '/' . $this->basename . '.zip';

        try {
            $this->createGedcomFile($gedcomPath, $gedcom);

            if (in_array($this->format, ['zipmedia', 'gedzip'])) {
                $this->createZipWithMedia($zipPath, $gedcomPath, $gedcomFile);
            } else {
                $this->createZipFile($zipPath, $gedcomPath, $gedcomFile);
            }

            return $this->streamZipDownload($zipPath, $gedcomPath);
        } catch (Exception $e) {
            $this->cleanupFiles([$gedcomPath, $zipPath]);
            throw new RuntimeException('Failed to create ZIP file: ' . $e->getMessage(), 0, $e);
        }
    }

    // --------------------------------------------------------------------------------------
    // UTILITY METHODS - Data Processing and Formatting
    // --------------------------------------------------------------------------------------

    /**
     * Sanitize text for GEDCOM output.
     *
     * @return string Sanitized text
     */
    protected function sanitizeText(string $text): string
    {
        // Remove line breaks and control characters, limit length
        $sanitized = preg_replace('/[\r\n\t]/', ' ', mb_trim($text));

        return mb_substr($sanitized, 0, 248); // GEDCOM line length limit
    }

    /**
     * Get appropriate line ending.
     *
     * @return string Line ending characters
     */
    protected function eol(): string
    {
        return "\r\n";
    }

    /**
     * Format a date for GEDCOM output.
     *
     * @return string Formatted GEDCOM date
     */
    protected function formatGedcomDate(CarbonInterface $date): string
    {
        return mb_strtoupper($date->format('j M Y'));
    }

    /**
     * Format coordinates for GEDCOM output.
     * GEDCOM 7.0 uses specific coordinate format requirements.
     *
     * @param  string|float  $coordinate
     * @param  string  $type  'latitude' or 'longitude'
     * @return string Formatted GEDCOM coordinate
     */
    protected function formatGedcomCoordinate($coordinate, string $type): string
    {
        $coord = (float) $coordinate;

        if ($type === 'latitude') {
            $direction = $coord >= 0 ? 'N' : 'S';
        } else {
            $direction = $coord >= 0 ? 'E' : 'W';
        }

        // Absolute value for degrees
        $degrees = abs($coord);

        // GEDCOM 7 prefers up to 5 decimal places for precision
        return sprintf('%s%.5f', $direction, $degrees);
    }

    /**
     * Collapse multi-line text to a single GEDCOM-safe line.
     */
    protected function oneLine(string $text): string
    {
        return mb_trim(preg_replace('/\s+/', ' ', str_replace(["\r", "\n"], ' ', $text)));
    }

    // --------------------------------------------------------------------------------------
    // MEDIA OBJECT COLLECTION
    // --------------------------------------------------------------------------------------

    /**
     * Collect all media objects for the individuals being exported.
     *
     * @param  Collection<Person>  $individuals
     */
    private function collectMediaObjects(Collection $individuals): void
    {
        $this->mediaObjects = [];
        $this->mediaFiles   = [];

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
     * Get images for a specific person.
     *
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
            ->filter(function ($file) {
                $filename = basename($file);

                return str_ends_with($filename, '.webp') &&
                       ! str_ends_with($filename, '_medium.webp') &&
                       ! str_ends_with($filename, '_small.webp');
            })
            ->map(function ($originalFile) {
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
     * Generate a descriptive title for an image.
     */
    private function generateImageTitle(string $filename): string
    {
        // Convert filename to readable title
        $title = str_replace(['_', '-'], ' ', $filename);
        $title = ucwords($title);

        return $title;
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Core Structure Methods (Enhanced)
    // --------------------------------------------------------------------------------------

    /**
     * Build complete GEDCOM content.
     *
     * Override this method to change the overall GEDCOM structure or add
     * additional record types (sources, repositories, notes, etc.).
     *
     * @param  Collection<Person>  $individuals
     * @return string Complete GEDCOM content
     */
    private function buildGedcom(Collection $individuals, Collection $couples): string
    {
        $submitter   = $this->getSubmitter();
        $submitterId = $submitter ? "@I{$submitter->id}@" : '@SUB1@';

        // Build family structures for GEDCOM
        $gedcomFamilies = $this->buildGedcomFamilies($individuals, $couples);
        $famsMapping    = $this->buildFamilyMapping($couples, $gedcomFamilies);

        $gedcom = '';
        $gedcom .= $this->buildHeader($submitterId);
        $gedcom .= $this->buildSubmitterRecord($submitter);
        $gedcom .= $this->buildIndividuals($individuals, $famsMapping);
        $gedcom .= $this->buildFamilies($gedcomFamilies);
        $gedcom .= $this->buildMediaRecords();
        $gedcom .= $this->buildAdditionalRecords($individuals, $gedcomFamilies);
        $gedcom .= $this->buildFooter();

        return $gedcom;
    }

    /**
     * Get the submitter for this GEDCOM file.
     *
     * Override to implement custom submitter logic.
     */
    private function getSubmitter(): ?User
    {
        return auth()->user() ?? null;
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Individual Records (Enhanced with Media)
    // --------------------------------------------------------------------------------------

    /**
     * Build a single individual record.
     *
     * Override to add custom fields or modify record structure.
     *
     * @param  array<int, array<int>>  $famsMapping
     * @return string Individual GEDCOM record
     */
    private function buildIndividualRecord(Person $person, array $famsMapping): string
    {
        $lines   = [];
        $lines[] = "0 @I{$person->id}@ INDI";

        // Core information
        $lines = array_merge($lines, $this->buildNameFields($person));
        $lines = array_merge($lines, $this->buildSexField($person));
        $lines = array_merge($lines, $this->buildBirthFields($person));
        $lines = array_merge($lines, $this->buildDeathFields($person));
        $lines = array_merge($lines, $this->buildNoteFields($person));

        // Family relationships
        $lines = array_merge($lines, $this->buildFamilyRelationships($person, $famsMapping));

        // Media objects (photos)
        $lines = array_merge($lines, $this->buildIndividualMediaFields($person));

        // Additional fields - override to add more
        $lines = array_merge($lines, $this->buildAdditionalIndividualFields($person));

        return implode($this->eol(), $lines) . $this->eol();
    }

    /**
     * Build media object references for an individual.
     *
     * @return array<string> Media reference lines
     */
    private function buildIndividualMediaFields(Person $person): array
    {
        $lines = [];

        if (isset($this->mediaObjects[$person->id])) {
            foreach ($this->mediaObjects[$person->id] as $media) {
                $lines[] = "1 OBJE @M{$media['id']}@";
            }
        }

        return $lines;
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Media Records
    // --------------------------------------------------------------------------------------

    /**
     * Build all media object records.
     *
     * @return string All media records
     */
    private function buildMediaRecords(): string
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
     * Build a single media object record.
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
            $lines[] = "2 TITL {$this->sanitizeText($media['title'])}";
        }

        // Notes or additional metadata could be added here

        return implode($this->eol(), $lines) . $this->eol();
    }

    // --------------------------------------------------------------------------------------
    // ZIP WITH MEDIA CREATION
    // --------------------------------------------------------------------------------------

    /**
     * Create ZIP archive with media files.
     *
     * @param  string  $zipPath  ZIP file path
     * @param  string  $gedcomPath  GEDCOM file path
     * @param  string  $gedcomFile  GEDCOM filename for archive
     *
     * @throws RuntimeException When ZIP creation fails
     */
    private function createZipWithMedia(string $zipPath, string $gedcomPath, string $gedcomFile): void
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
        foreach ($this->mediaFiles as $mediaPath) {
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

        Log::info('Created ZIP with ' . count($this->mediaFiles) . ' media files');
    }

    // --------------------------------------------------------------------------------------
    // [Previous methods remain unchanged - keeping the rest of the original class]
    // --------------------------------------------------------------------------------------

    /**
     * Debug version with logging to identify the exact issue
     */
    private function buildGedcomFamilies(Collection $individuals, Collection $couples): \Illuminate\Support\Collection
    {
        Log::info('Starting family building with ' . $couples->count() . ' couples and ' . $individuals->count() . ' individuals');

        $gedcomFamilies            = collect();
        $this->parentFamilyMapping = [];

        // Step 1: Create unique families for each couple pair
        $uniquePairs = [];

        foreach ($couples as $couple) {
            $ids = [$couple->person1_id, $couple->person2_id];
            sort($ids);
            $pairKey = implode('-', $ids);

            if (! isset($uniquePairs[$pairKey])) {
                $uniquePairs[$pairKey] = $couple->id;

                $family = (object) [
                    'id'            => $couple->id,
                    'type'          => 'couple',
                    'person1_id'    => $couple->person1_id,
                    'person2_id'    => $couple->person2_id,
                    'relationships' => $couples->where('person1_id', $couple->person1_id)
                        ->where('person2_id', $couple->person2_id)
                        ->merge($couples->where('person1_id', $couple->person2_id)
                            ->where('person2_id', $couple->person1_id)),
                    'children' => collect(),
                ];

                $gedcomFamilies->push($family);
                $this->parentFamilyMapping[$pairKey] = $couple->id;

                Log::info("Created couple family {$couple->id} for pair {$pairKey}");
            } else {
                $this->parentFamilyMapping[$pairKey] = $uniquePairs[$pairKey];
            }
        }

        Log::info('Created ' . $gedcomFamilies->count() . ' couple families');

        // Step 2: Process children - assign to existing families or create parent-only families
        $nextParentId = 20000; // Use a clearly different range

        foreach ($individuals as $person) {
            $familyId = null;

            // Check father_id/mother_id combination
            if ($person->father_id || $person->mother_id) {
                $parentKey = $this->getParentKey($person->father_id, $person->mother_id);

                if (isset($this->parentFamilyMapping[$parentKey])) {
                    $familyId = $this->parentFamilyMapping[$parentKey];
                    Log::info("Person {$person->id} assigned to existing family {$familyId} via parents {$parentKey}");
                } else {
                    // Create parent-only family
                    $familyId                              = $nextParentId++;
                    $this->parentFamilyMapping[$parentKey] = $familyId;

                    $parentFamily = (object) [
                        'id'         => $familyId,
                        'type'       => 'parent',
                        'person1_id' => $person->father_id,
                        'person2_id' => $person->mother_id,
                        'children'   => collect(),
                    ];

                    $gedcomFamilies->push($parentFamily);
                    Log::info("Created parent-only family {$familyId} for person {$person->id} with parents {$parentKey}");
                }
            }
            // Check parents_id
            elseif ($person->parents_id) {
                $familyId = $person->parents_id;
                Log::info("Person {$person->id} assigned to family {$familyId} via parents_id");
            }

            // Add person as child to their family
            if ($familyId) {
                $family = $gedcomFamilies->firstWhere('id', $familyId);
                if ($family) {
                    $family->children->push($person);
                } else {
                    Log::warning("Could not find family {$familyId} for person {$person->id}");
                }
            }
        }

        Log::info('Final family count: ' . $gedcomFamilies->count());

        return $gedcomFamilies;
    }

    /**
     * Generate a unique key for a parent combination.
     */
    private function getParentKey(?int $parent1Id, ?int $parent2Id): string
    {
        return collect([$parent1Id, $parent2Id])
            ->filter()
            ->sort()
            ->implode('-');
    }

    /**
     * Build all individual records.
     *
     * @param  Collection<Person>  $individuals
     * @param  array<int, array<int>>  $famsMapping  Person ID to family IDs mapping
     * @return string All individual records
     */
    private function buildIndividuals(Collection $individuals, array $famsMapping): string
    {
        $gedcom = '';

        foreach ($individuals as $person) {
            $gedcom .= $this->buildIndividualRecord($person, $famsMapping);
        }

        return $gedcom;
    }

    /**
     * Build name fields for an individual.
     *
     * @return array<string> Name field lines
     */
    private function buildNameFields(Person $person): array
    {
        $lines = [];

        $given = mb_trim((string) ($person->firstname ?? ''));
        $surn  = mb_trim((string) ($person->surname ?? ''));

        // Primary NAME
        $lines[] = '1 NAME ' . ($given !== '' || $surn !== '' ? "{$given} /{$surn}/" : '/ /');

        if ($given !== '') {
            $lines[] = "2 GIVN {$given}";
        }
        if ($surn !== '') {
            $lines[] = "2 SURN {$surn}";
        }
        if (! empty($person->nickname)) {
            $lines[] = '2 NICK ' . $this->oneLine((string) $person->nickname);
        }

        // Birth name (as an alternate NAME with TYPE birth)
        if (! empty($person->birthname)) {
            $birthSurn = mb_trim((string) $person->birthname);

            $lines[] = '1 NAME ' . ($given !== '' || $birthSurn !== '' ? "{$given} /{$birthSurn}/" : '/ /');
            $lines[] = '2 TYPE birth';

            if ($given !== '') {
                $lines[] = "2 GIVN {$given}";
            }
            if ($birthSurn !== '') {
                $lines[] = "2 SURN {$birthSurn}";
            }
        }

        return $lines;
    }

    /**
     * Build sex field for an individual.
     *
     * @return array<string> Sex field lines
     */
    private function buildSexField(Person $person): array
    {
        $lines = [];
        $sex   = mb_strtoupper((string) ($person->sex ?? ''));
        if (in_array($sex, ['M', 'F', 'U'], true)) {
            $lines[] = "1 SEX {$sex}";
        }

        return $lines;
    }

    /**
     * Build birth-related fields for an individual.
     *
     * @return array<string> Birth field lines
     */
    private function buildBirthFields(Person $person): array
    {
        $lines = [];
        if ($person->dob || $person->yob || $person->pob) {
            $lines[] = '1 BIRT';

            // Full date of birth
            if ($person->dob) {
                if ($d = $this->formatGedcomDate($person->dob)) {
                    $lines[] = "2 DATE {$d}";
                }
            }
            // Fallback to year of birth if no full date
            elseif ($person->yob) {
                $lines[] = '2 DATE ' . (int) $person->yob;
            }

            // Place of birth
            if ($person->pob) {
                $lines[] = '2 PLAC ' . $this->sanitizeText((string) $person->pob);
            }
        }

        return $lines;
    }

    /**
     * Build death-related fields for an individual.
     *
     * @return array<string> Death field lines
     */
    private function buildDeathFields(Person $person): array
    {
        $lines = [];
        if ($person->dod || $person->yod || $person->pod) {
            $lines[] = '1 DEAT';

            // Full date of death
            if ($person->dod && $d = $this->formatGedcomDate($person->dod)) {
                $lines[] = "2 DATE {$d}";
            } elseif ($person->yod) {
                $lines[] = '2 DATE ' . (int) $person->yod;
            }

            // Place of death
            if ($person->pod) {
                $lines[] = '2 PLAC ' . $this->sanitizeText((string) $person->pod);
            }

            // Cemetery - Fixed MAP tag placement and coordinate format
            $cemetery_name               = $person->getMetadataValue('cemetery_location_name');
            $cemetery_address            = $person->getMetadataValue('cemetery_location_address');
            $cemetery_location_latitude  = $person->getMetadataValue('cemetery_location_latitude');
            $cemetery_location_longitude = $person->getMetadataValue('cemetery_location_longitude');

            if ($cemetery_name || $cemetery_address || $cemetery_location_latitude || $cemetery_location_longitude) {
                $lines[] = '1 BURI';

                // Build cemetery place name with coordinates
                $cemeteryPlace = $cemetery_name ?: '';

                if ($cemeteryPlace) {
                    $lines[] = '2 PLAC ' . $this->sanitizeText($cemeteryPlace);

                    // Add map coordinates under PLAC (GEDCOM 7.0 compliant format)
                    if ($cemetery_location_latitude || $cemetery_location_longitude) {
                        $lines[] = '3 MAP';
                        if ($cemetery_location_latitude) {
                            $lines[] = '4 LATI ' . $this->formatGedcomCoordinate($cemetery_location_latitude, 'latitude');
                        }
                        if ($cemetery_location_longitude) {
                            $lines[] = '4 LONG ' . $this->formatGedcomCoordinate($cemetery_location_longitude, 'longitude');
                        }
                    }
                }

                // Cemetery address (ADDR) with multi-line support
                if ($cemetery_address) {
                    $lines = array_merge(
                        $lines,
                        $this->exportMultilineText('ADDR', $cemetery_address, 2)
                    );
                }
            }
        }

        return $lines;
    }

    /**
     * Build note-related fields for an individual.
     *
     * @return array<string> Note field lines
     */
    private function buildNoteFields(Person $person): array
    {
        $lines = [];
        if (! empty($person->summary)) {
            $lines = array_merge($lines, $this->exportMultilineText('NOTE', $person->summary, 1));
        }

        return $lines;
    }

    /**
     * Build family relationship fields for an individual.
     *
     * @param  array<int, array<int>>  $famsMapping
     * @return array<string> Family relationship field lines
     */
    private function buildFamilyRelationships(Person $person, array $famsMapping): array
    {
        $lines = [];

        // Child in family (FAMC) - find parent family
        $parentFamilyId = $this->getPersonParentFamilyId($person);

        if ($parentFamilyId) {
            $lines[] = "1 FAMC @F{$parentFamilyId}@";
        }

        // Spouse in families (FAMS) - from couples table
        if (! empty($famsMapping[$person->id])) {
            foreach ($famsMapping[$person->id] as $familyId) {
                $lines[] = "1 FAMS @F{$familyId}@";
            }
        }

        return $lines;
    }

    /**
     * Get the parent family ID for a person based on their parent relationships.
     */
    private function getPersonParentFamilyId(Person $person): ?int
    {
        // Case 1: Individual parent fields
        if ($person->father_id || $person->mother_id) {
            $parentKey = $this->getParentKey($person->father_id, $person->mother_id);

            return $this->parentFamilyMapping[$parentKey] ?? null;
        }

        // Case 2: parents_id points to a couple
        if ($person->parents_id) {
            return $person->parents_id;
        }

        return null;
    }

    /**
     * Build additional individual fields.
     *
     * Override this method to add custom fields like:
     * - OCCU (Occupation)
     * - RELI (Religion)
     * - NOTE (Notes)
     * - SOUR (Sources)
     *
     * @return array<string> Additional field lines
     */
    private function buildAdditionalIndividualFields(Person $person): array
    {
        return [];
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Family Records
    // --------------------------------------------------------------------------------------

    /**
     * Build all family records.
     *
     * @return string All family records
     */
    private function buildFamilies(\Illuminate\Support\Collection $gedcomFamilies): string
    {
        $gedcom = '';

        foreach ($gedcomFamilies as $family) {
            $gedcom .= $this->buildFamilyRecord($family);
        }

        return $gedcom;
    }

    /**
     * Build a single family record.
     *
     * Override to add custom family fields or modify structure.
     *
     * @param  mixed  $family  GEDCOM family object
     * @return string Family GEDCOM record
     */
    private function buildFamilyRecord($family): string
    {
        $fid   = "@F{$family->id}@";
        $lines = ["0 {$fid} FAM"];

        // Parents/Spouses
        if ($family->person1_id) {
            $lines[] = "1 HUSB @I{$family->person1_id}@";
        }
        if ($family->person2_id) {
            $lines[] = "1 WIFE @I{$family->person2_id}@";
        }

        // Marriage/relationship information (only for couple type families)
        if ($family->type === 'couple') {
            $lines = array_merge($lines, $this->buildFamilyFields($family));
        }

        // Children
        $lines = array_merge($lines, $this->buildChildrenFields($family));

        return implode($this->eol(), $lines) . $this->eol();
    }

    /**
     * Build marriage-related fields for a family.
     *
     * @param  mixed  $family
     * @return array<string> Marriage field lines
     */
    private function buildFamilyFields($family): array
    {
        $lines = [];

        // Handle multiple relationship periods if they exist
        if (isset($family->relationships)) {
            foreach ($family->relationships as $relationship) {
                // Marriage event if marked as married
                if ($relationship->is_married) {
                    $lines[] = '1 MARR';

                    if ($relationship->date_start) {
                        if ($d = $this->formatGedcomDate($relationship->date_start)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }

                    // If relationship has ended and they were married, add divorce
                    if ($relationship->has_ended && $relationship->date_end) {
                        $lines[] = '1 DIV';
                        if ($d = $this->formatGedcomDate($relationship->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
                } else {
                    // Non-married relationship
                    $lines[] = '1 EVEN';
                    $lines[] = '2 TYPE Relationship';

                    if ($relationship->date_start) {
                        if ($d = $this->formatGedcomDate($relationship->date_start)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }

                    // If relationship has ended
                    if ($relationship->has_ended && $relationship->date_end) {
                        $lines[] = '1 EVEN';
                        $lines[] = '2 TYPE End of relationship';
                        if ($d = $this->formatGedcomDate($relationship->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
                }
            }
        } else {
            // Fallback for legacy single relationship format
            if ($family->is_married) {
                $lines[] = '1 MARR';

                if ($family->date_start) {
                    if ($d = $this->formatGedcomDate($family->date_start)) {
                        $lines[] = "2 DATE {$d}";
                    }
                }
            }

            if ($family->has_ended) {
                if ($family->is_married) {
                    $lines[] = '1 DIV';
                    if ($family->date_end) {
                        if ($d = $this->formatGedcomDate($family->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
                } else {
                    $lines[] = '1 EVEN';
                    $lines[] = '2 TYPE End of relationship';
                    if ($family->date_end) {
                        if ($d = $this->formatGedcomDate($family->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
                }
            }

            if (! $family->is_married && $family->date_start) {
                $lines[] = '1 EVEN';
                $lines[] = '2 TYPE Beginning of relationship';
                if ($d = $this->formatGedcomDate($family->date_start)) {
                    $lines[] = "2 DATE {$d}";
                }
            }
        }

        return $lines;
    }

    /**
     * Build children fields for a family.
     *
     * @param  mixed  $family
     * @return array<string> Children field lines
     */
    private function buildChildrenFields($family): array
    {
        $lines = [];

        // Children are already collected in the family object
        foreach ($family->children as $child) {
            $lines[] = "1 CHIL @I{$child->id}@";
        }

        return $lines;
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Additional Records
    // --------------------------------------------------------------------------------------

    /**
     * Build additional record types.
     *
     * Override this method to add:
     * - Source records (SOUR)
     * - Repository records (REPO)
     * - Note records (NOTE)
     *
     * @param  Collection<Person>  $individuals
     * @return string Additional records
     */
    private function buildAdditionalRecords(Collection $individuals, \Illuminate\Support\Collection $gedcomFamilies): string
    {
        return '';
    }

    /**
     * Build GEDCOM header record.
     *
     * Override to customize header information or add additional header tags.
     * Compliant with GEDCOM 7.0 specification.
     *
     * @param  string  $submitterId  Reference to submitter record
     * @return string GEDCOM header content
     */
    private function buildHeader(string $submitterId): string
    {
        $now = now();

        $headerLines = [
            '0 HEAD',
            '1 GEDC',
            '2 VERS ' . self::GEDCOM_VERSION,
            '1 SOUR ' . $this->getSourceName(),
            '2 VERS 1.0',
            '2 NAME ' . $this->teamname,
            '2 CORP ' . $this->getSourceCorporation(),
            '1 DATE ' . mb_strtoupper($now->format('j M Y')),
            '2 TIME ' . $now->format('H:i:s'),
            "1 SUBM {$submitterId}",
            '1 LANG ' . app()->getLocale(),
        ];

        return implode($this->eol(), $headerLines) . $this->eol();
    }

    /**
     * Build submitter record.
     *
     * Override to customize submitter information.
     *
     * @return string GEDCOM submitter record
     */
    private function buildSubmitterRecord(?User $submitter): string
    {
        if (! $submitter) {
            return '0 @SUB1@ SUBM' . $this->eol() .
                   '1 NAME Unknown' . $this->eol();
        }

        $submitterId = "@I{$submitter->id}@";
        $name        = mb_trim(($submitter->firstname ?? '') . ' ' . ($submitter->surname ?? ''));

        return "0 {$submitterId} SUBM" . $this->eol() .
               "1 NAME {$name}" . $this->eol();
    }

    /**
     * Build GEDCOM footer/trailer record.
     *
     * @return string GEDCOM trailer content
     */
    private function buildFooter(): string
    {
        return '0 TRLR' . $this->eol();
    }

    /**
     * Fixed family mapping that ensures every adult gets FAMS tags
     */
    private function buildFamilyMapping(Collection $couples, \Illuminate\Support\Collection $gedcomFamilies): array
    {
        $famsMapping = [];

        foreach ($gedcomFamilies as $family) {
            // Every family where a person is person1 or person2 (i.e., an adult/parent)
            // should result in a FAMS tag for that person

            if ($family->person1_id) {
                if (! isset($famsMapping[$family->person1_id])) {
                    $famsMapping[$family->person1_id] = [];
                }
                $famsMapping[$family->person1_id][] = $family->id;
                Log::info("Person {$family->person1_id} gets FAMS for family {$family->id}");
            }

            if ($family->person2_id) {
                if (! isset($famsMapping[$family->person2_id])) {
                    $famsMapping[$family->person2_id] = [];
                }
                $famsMapping[$family->person2_id][] = $family->id;
                Log::info("Person {$family->person2_id} gets FAMS for family {$family->id}");
            }
        }

        Log::info('FAMS mapping created for ' . count($famsMapping) . ' people');

        return $famsMapping;
    }

    // --------------------------------------------------------------------------------------
    // CONFIGURATION METHODS - Override for Customization
    // --------------------------------------------------------------------------------------

    /**
     * Get the source name for the GEDCOM header.
     *
     * @return string Source name
     */
    private function getSourceName(): string
    {
        return config('app.name');
    }

    /**
     * Get the source corporation for the GEDCOM header.
     *
     * @return string Corporation name
     */
    private function getSourceCorporation(): string
    {
        return config('app.name');
    }

    // --------------------------------------------------------------------------------------
    // FILE HANDLING
    // --------------------------------------------------------------------------------------

    /**
     * Get file extension based on format.
     *
     * @return string File extension
     */
    private function getExtension(string $format): string
    {
        return match ($format) {
            'gedcom' => '.ged',
            'zip', 'zipmedia' => '.zip',
            'gedzip' => '.gdz',
            default  => '.ged',
        };
    }

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
     * Create ZIP archive.
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

    /**
     * Stream ZIP file download.
     *
     * @param  string  $zipPath  ZIP file path
     * @param  string  $gedcomPath  GEDCOM file path for cleanup
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
     * Clean up temporary files.
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

    /**
     * Export a multi-line text field with CONC/CONT support
     *
     * @param  string  $tag  GEDCOM tag (NOTE, ADDR, OCCU, EDUC, etc.)
     * @param  string  $text  Multi-line text
     * @param  int  $level  Base GEDCOM level
     * @return array<string>
     */
    private function exportMultilineText(string $tag, string $text, int $level = 1): array
    {
        $lines = [];
        $parts = preg_split('/\r\n|\r|\n/', $text);

        if (empty($parts)) {
            return $lines;
        }

        foreach ($parts as $i => $line) {
            $line = $this->sanitizeText($line);

            // First line → main tag
            $lineText = $i === 0 ? "{$level} {$tag} {$line}" : ($level + 1) . ' CONT ' . $line;

            // Split long lines > 255 chars using CONC
            while (mb_strlen($lineText) > 255) {
                $lines[]  = mb_substr($lineText, 0, 255);
                $lineText = ($level + 1) . ' CONC ' . mb_substr($lineText, 255);
            }

            $lines[] = $lineText;
        }

        return $lines;
    }
}
