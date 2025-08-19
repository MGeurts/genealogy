<?php

declare(strict_types=1);

namespace App\Php\Gedcom;

use App\Models\Person;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

/**
 * GEDCOM Export Class
 *
 * Handles the export of genealogical data to GEDCOM format with support for:
 * - Multiple output formats (GEDCOM, ZIP, GEDZIP)
 * - Multiple encodings (UTF-8, Unicode, ANSEL, ASCII, ANSI)
 * - Flexible line endings (Windows/Unix)
 * - Extensible record building system
 *
 * @author Your Name
 *
 * @version 1.0
 *
 * @since Laravel 12
 *
 * @example
 * $export = new Export('my_family_tree', 'gedcom', 'utf8');
 * $gedcom = $export->export($individuals, $families);
 * return $export->downloadGedcom($gedcom);
 */
final class Export
{
    // --------------------------------------------------------------------------------------
    // CONSTANTS - Configuration and Standards
    // --------------------------------------------------------------------------------------

    /** @var array<string> Supported export formats */
    private const SUPPORTED_FORMATS = ['gedcom', 'zip', 'zipmedia', 'gedzip'];

    /** @var array<string> Supported character encodings */
    private const SUPPORTED_ENCODINGS = ['utf8', 'unicode', 'ansel', 'ascii', 'ansi'];

    /** @var array<string> Supported line ending types */
    private const SUPPORTED_LINE_ENDINGS = ['windows', 'unix'];

    /** @var string Current GEDCOM version being used */
    private const GEDCOM_VERSION = '7.0';

    /** @var int Default buffer size for file streaming */
    private const STREAM_BUFFER_SIZE = 8192;

    // --------------------------------------------------------------------------------------
    // PROPERTIES
    // --------------------------------------------------------------------------------------

    /** @var string Final filename with extension */
    public readonly string $filename;

    /** @var string Base filename without extension */
    private readonly string $basename;

    /** @var string File extension based on format */
    private readonly string $extension;

    // --------------------------------------------------------------------------------------
    // CONSTRUCTOR & VALIDATION
    // --------------------------------------------------------------------------------------

    /**
     * Create a new GEDCOM export instance.
     *
     * @param  string  $basename  Base filename (without extension)
     * @param  string  $format  Export format (gedcom|zip|zipmedia|gedzip)
     * @param  string  $encoding  Character encoding (utf8|unicode|ansel|ascii|ansi)
     * @param  string  $line_endings  Line ending type (windows|unix)
     *
     * @throws InvalidArgumentException When parameters are invalid
     */
    public function __construct(
        string $basename,
        public readonly string $format = 'gedcom',
        public readonly string $encoding = 'utf8',
        public readonly string $line_endings = 'windows'
    ) {
        $this->validateInputs($basename, $format, $encoding, $line_endings);

        $this->basename  = $basename;
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
     * This is the main entry point for generating GEDCOM content. Override
     * individual build methods to customize specific record types.
     *
     * @param  Collection<Person>  $individuals  Collection of Person models
     * @param  Collection  $families  Collection of family/couple models
     * @return string Complete GEDCOM content
     *
     * @throws InvalidArgumentException When data is empty
     */
    public function export(Collection $individuals, Collection $families): string
    {
        if ($individuals->isEmpty() && $families->isEmpty()) {
            throw new InvalidArgumentException('Cannot export empty genealogy data');
        }

        return $this->buildGedcom($individuals, $families);
    }

    /**
     * Download GEDCOM content as a direct file.
     *
     * @param  string  $gedcom  GEDCOM content to download
     * @return StreamedResponse Laravel streamed response
     */
    public function downloadGedcom(string $gedcom): StreamedResponse
    {
        $encodedGedcom = $this->applyEncoding($gedcom);

        return Response::streamDownload(
            fn () => print ($encodedGedcom),
            $this->filename,
            $this->getGedcomHeaders()
        );
    }

    /**
     * Download GEDCOM content as a ZIP archive.
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
            $this->createZipFile($zipPath, $gedcomPath, $gedcomFile);

            return $this->streamZipDownload($zipPath, $gedcomPath);
        } catch (Exception $e) {
            $this->cleanupFiles([$gedcomPath, $zipPath]);
            throw new RuntimeException('Failed to create ZIP file: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Validate constructor inputs.
     *
     *
     * @throws InvalidArgumentException
     */
    private function validateInputs(string $basename, string $format, string $encoding, string $line_endings): void
    {
        if (empty(mb_trim($basename))) {
            throw new InvalidArgumentException('Basename cannot be empty');
        }

        if (! in_array($format, self::SUPPORTED_FORMATS, true)) {
            throw new InvalidArgumentException(
                sprintf('Unsupported format "%s". Supported: %s', $format, implode(', ', self::SUPPORTED_FORMATS))
            );
        }

        if (! in_array($encoding, self::SUPPORTED_ENCODINGS, true)) {
            throw new InvalidArgumentException(
                sprintf('Unsupported encoding "%s". Supported: %s', $encoding, implode(', ', self::SUPPORTED_ENCODINGS))
            );
        }

        if (! in_array($line_endings, self::SUPPORTED_LINE_ENDINGS, true)) {
            throw new InvalidArgumentException(
                sprintf('Unsupported line endings "%s". Supported: %s', $line_endings, implode(', ', self::SUPPORTED_LINE_ENDINGS))
            );
        }
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Core Structure Methods
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
    private function buildGedcom(Collection $individuals, Collection $families): string
    {
        $submitter   = $this->getSubmitter($individuals);
        $submitterId = $submitter ? "@I{$submitter->id}@" : '@SUB1@';
        $famsMapping = $this->buildFamilyMapping($families);

        $gedcom = '';
        $gedcom .= $this->buildHeader($submitterId);
        $gedcom .= $this->buildSubmitterRecord($submitter);
        $gedcom .= $this->buildIndividuals($individuals, $famsMapping);
        $gedcom .= $this->buildFamilies($families);
        $gedcom .= $this->buildAdditionalRecords($individuals, $families);
        $gedcom .= $this->buildFooter();

        return $gedcom;
    }

    /**
     * Get the submitter for this GEDCOM file.
     *
     * Override to implement custom submitter logic.
     *
     * @param  Collection<Person>  $individuals
     */
    private function getSubmitter(Collection $individuals): ?Person
    {
        return $individuals->first();
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Header and Footer
    // --------------------------------------------------------------------------------------

    /**
     * Build GEDCOM header record.
     *
     * Override to customize header information or add additional header tags.
     *
     * @param  string  $submitterId  Reference to submitter record
     * @return string GEDCOM header content
     */
    private function buildHeader(string $submitterId): string
    {
        $now     = now();
        $appName = config('app.name', 'Unknown');

        $headerLines = [
            '0 HEAD',
            "1 SOUR {$appName}",
            '2 VERS 1.0',
            '2 NAME ' . $this->getSourceName(),
            '2 CORP ' . $this->getSourceCorporation(),
            '1 DEST ANY',
            '1 DATE ' . mb_strtoupper($now->format('j M Y')),
            '2 TIME ' . $now->format('H:i:s'),
            "1 SUBM {$submitterId}",
            '1 FILE ' . $this->filename,
            '1 GEDC',
            '2 VERS ' . self::GEDCOM_VERSION,
            '2 FORM LINEAGE-LINKED',
            '1 CHAR ' . $this->encodingLabel(),
            '1 LANG ' . app()->getLocale(),
        ];

        return implode($this->eol(), $headerLines) . $this->eol();
    }

    /**
     * Build submitter record.
     *
     * Override to customize submitter information.
     *
     *
     * @return string GEDCOM submitter record
     */
    private function buildSubmitterRecord(?Person $submitter): string
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

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Individual Records
    // --------------------------------------------------------------------------------------

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
     * Build a single individual record.
     *
     * Override to add custom fields or modify record structure.
     *
     * @param  array<int, array<int>>  $famsMapping
     * @return string Individual GEDCOM record
     */
    private function buildIndividualRecord(Person $person, array $famsMapping): string
    {
        $id    = "@I{$person->id}@";
        $lines = ["0 {$id} INDI"];

        // Core information
        $lines = array_merge($lines, $this->buildNameFields($person));
        $lines = array_merge($lines, $this->buildSexField($person));
        $lines = array_merge($lines, $this->buildBirthFields($person));
        $lines = array_merge($lines, $this->buildDeathFields($person));

        // Family relationships
        $lines = array_merge($lines, $this->buildFamilyRelationships($person, $famsMapping));

        // Additional fields - override to add more
        $lines = array_merge($lines, $this->buildAdditionalIndividualFields($person));

        return implode($this->eol(), $lines) . $this->eol();
    }

    /**
     * Build name fields for an individual.
     *
     *
     * @return array<string> Name field lines
     */
    private function buildNameFields(Person $person): array
    {
        $firstname = mb_trim($person->firstname ?? '');
        $surname   = mb_trim($person->surname ?? '');

        return ["1 NAME {$firstname} /{$surname}/"];
    }

    /**
     * Build sex field for an individual.
     *
     *
     * @return array<string> Sex field lines
     */
    private function buildSexField(Person $person): array
    {
        $sex = in_array(mb_strtoupper($person->sex ?? ''), ['M', 'F', 'U'])
            ? mb_strtoupper($person->sex)
            : 'U';

        return ["1 SEX {$sex}"];
    }

    /**
     * Build birth-related fields for an individual.
     *
     *
     * @return array<string> Birth field lines
     */
    private function buildBirthFields(Person $person): array
    {
        if (! $person->dob && ! $person->pob) {
            return [];
        }

        $lines = ['1 BIRT'];

        if ($person->dob) {
            $lines[] = '2 DATE ' . $this->formatGedcomDate($person->dob);
        }

        if ($person->pob) {
            $lines[] = '2 PLAC ' . $this->sanitizeText($person->pob);
        }

        return $lines;
    }

    /**
     * Build death-related fields for an individual.
     *
     *
     * @return array<string> Death field lines
     */
    private function buildDeathFields(Person $person): array
    {
        if (! $person->dod && ! $person->pod) {
            return [];
        }

        $lines = ['1 DEAT'];

        if ($person->dod) {
            $lines[] = '2 DATE ' . $this->formatGedcomDate($person->dod);
        }

        if ($person->pod) {
            $lines[] = '2 PLAC ' . $this->sanitizeText($person->pod);
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

        // Child in family
        if ($person->parents_id) {
            $lines[] = "1 FAMC @F{$person->parents_id}@";
        }

        // Spouse in families
        if (! empty($famsMapping[$person->id])) {
            foreach ($famsMapping[$person->id] as $familyId) {
                $lines[] = "1 FAMS @F{$familyId}@";
            }
        }

        return $lines;
    }

    /**
     * Build additional individual fields.
     *
     * Override this method to add custom fields like:
     * - OCCU (Occupation)
     * - RELI (Religion)
     * - NOTE (Notes)
     * - SOUR (Sources)
     * - OBJE (Media objects)
     *
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
     *
     * @return string All family records
     */
    private function buildFamilies(Collection $families): string
    {
        $gedcom = '';

        foreach ($families as $couple) {
            $gedcom .= $this->buildFamilyRecord($couple);
        }

        return $gedcom;
    }

    /**
     * Build a single family record.
     *
     * Override to add custom family fields or modify structure.
     *
     * @param  mixed  $couple  Family/couple model
     * @return string Family GEDCOM record
     */
    private function buildFamilyRecord($couple): string
    {
        $fid   = "@F{$couple->id}@";
        $lines = ["0 {$fid} FAM"];

        // Spouses
        if ($couple->person1_id) {
            $lines[] = "1 HUSB @I{$couple->person1_id}@";
        }
        if ($couple->person2_id) {
            $lines[] = "1 WIFE @I{$couple->person2_id}@";
        }

        // Marriage information
        $lines = array_merge($lines, $this->buildMarriageFields($couple));

        // Children
        $lines = array_merge($lines, $this->buildChildrenFields($couple));

        // Additional family fields
        $lines = array_merge($lines, $this->buildAdditionalFamilyFields($couple));

        return implode($this->eol(), $lines) . $this->eol();
    }

    /**
     * Build marriage-related fields for a family.
     *
     * @param  mixed  $couple
     * @return array<string> Marriage field lines
     */
    private function buildMarriageFields($couple): array
    {
        if (! $couple->date_start) {
            return [];
        }

        return [
            '1 MARR',
            '2 DATE ' . $this->formatGedcomDate($couple->date_start),
        ];
    }

    /**
     * Build children fields for a family.
     *
     * @param  mixed  $couple
     * @return array<string> Children field lines
     */
    private function buildChildrenFields($couple): array
    {
        $lines = [];

        // TODO: Consider using eager loading to avoid N+1 queries
        $children = Person::where('parents_id', $couple->id)->get();

        foreach ($children as $child) {
            $lines[] = "1 CHIL @I{$child->id}@";
        }

        return $lines;
    }

    /**
     * Build additional family fields.
     *
     * Override this method to add custom fields like:
     * - DIVF (Divorce filed)
     * - DIV (Divorce)
     * - ENGA (Engagement)
     * - NOTE (Notes)
     * - SOUR (Sources)
     *
     * @param  mixed  $couple
     * @return array<string> Additional field lines
     */
    private function buildAdditionalFamilyFields($couple): array
    {
        return [];
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
     * - Media object records (OBJE)
     *
     * @param  Collection<Person>  $individuals
     * @return string Additional records
     */
    private function buildAdditionalRecords(Collection $individuals, Collection $families): string
    {
        return '';
    }

    // --------------------------------------------------------------------------------------
    // UTILITY METHODS - Data Processing and Formatting
    // --------------------------------------------------------------------------------------

    /**
     * Build mapping of person IDs to family IDs where they are spouses.
     *
     *
     * @return array<int, array<int>> Person ID => Family IDs mapping
     */
    private function buildFamilyMapping(Collection $families): array
    {
        $famsMapping = [];

        foreach ($families as $couple) {
            if ($couple->person1_id) {
                $famsMapping[$couple->person1_id][] = $couple->id;
            }
            if ($couple->person2_id) {
                $famsMapping[$couple->person2_id][] = $couple->id;
            }
        }

        return $famsMapping;
    }

    /**
     * Format a date for GEDCOM output.
     *
     *
     * @return string Formatted GEDCOM date
     */
    private function formatGedcomDate(CarbonInterface $date): string
    {
        return mb_strtoupper($date->format('j M Y'));
    }

    /**
     * Sanitize text for GEDCOM output.
     *
     *
     * @return string Sanitized text
     */
    private function sanitizeText(string $text): string
    {
        // Remove line breaks and control characters, limit length
        $sanitized = preg_replace('/[\r\n\t]/', ' ', mb_trim($text));

        return mb_substr($sanitized, 0, 248); // GEDCOM line length limit
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
        return config('gedcom.source_name', config('app.name', 'Genealogy Application'));
    }

    /**
     * Get the source corporation for the GEDCOM header.
     *
     * @return string Corporation name
     */
    private function getSourceCorporation(): string
    {
        return config('gedcom.corporation', 'Your Organization');
    }

    // --------------------------------------------------------------------------------------
    // ENCODING AND FILE HANDLING
    // --------------------------------------------------------------------------------------

    /**
     * Apply character encoding to GEDCOM content.
     *
     *
     * @return string Encoded content
     */
    private function applyEncoding(string $gedcom): string
    {
        try {
            return match ($this->encoding) {
                'utf8'    => $gedcom,
                'unicode' => mb_convert_encoding($gedcom, 'UTF-16BE', 'UTF-8'),
                'ansel'   => mb_convert_encoding($gedcom, 'ASCII', 'UTF-8'), // ANSEL approximation
                'ascii'   => mb_convert_encoding($gedcom, 'ASCII', 'UTF-8'),
                'ansi'    => mb_convert_encoding($gedcom, 'CP1252', 'UTF-8'),
                default   => $gedcom,
            };
        } catch (Exception $e) {
            Log::warning('Encoding conversion failed', [
                'encoding' => $this->encoding,
                'error'    => $e->getMessage(),
            ]);

            return $gedcom; // Fallback to original
        }
    }

    /**
     * Get file extension based on format.
     *
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
     * Get encoding label for GEDCOM header.
     *
     * @return string Encoding label
     */
    private function encodingLabel(): string
    {
        return match ($this->encoding) {
            'utf8'    => 'UTF-8',
            'unicode' => 'UNICODE',
            'ansel'   => 'ANSEL',
            'ascii'   => 'ASCII',
            'ansi'    => 'ANSI',
            default   => mb_strtoupper($this->encoding),
        };
    }

    /**
     * Get appropriate line ending based on configuration.
     *
     * @return string Line ending characters
     */
    private function eol(): string
    {
        return $this->line_endings === 'windows' ? "\r\n" : "\n";
    }

    /**
     * Get HTTP headers for GEDCOM download.
     *
     * @return array<string, string> HTTP headers
     */
    private function getGedcomHeaders(): array
    {
        return [
            'Content-Type'        => 'text/plain; charset=' . $this->encoding,
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
        $encodedGedcom = $this->applyEncoding($gedcom);
        if (file_put_contents($path, $encodedGedcom) === false) {
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
}
