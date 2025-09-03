<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * GEDCOM Export Orchestrator
 *
 * Main coordinator for GEDCOM export process, managing specialized builders
 * for different record types while maintaining the same public API as the
 * original monolithic class.
 *
 * Usage:
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

    // --------------------------------------------------------------------------------------
    // PROPERTIES
    // --------------------------------------------------------------------------------------

    /** @var string Final filename with extension */
    private readonly string $filename;

    /** @var string File extension based on format */
    private readonly string $extension;

    /** @var GedcomHeaderBuilder Handles headers and submitter records */
    private GedcomHeaderBuilder $headerBuilder;

    /** @var GedcomIndividualBuilder Handles person records */
    private GedcomIndividualBuilder $individualBuilder;

    /** @var GedcomFamilyBuilder Handles family records and relationships */
    private GedcomFamilyBuilder $familyBuilder;

    /** @var GedcomMediaBuilder Handles media objects and files */
    private GedcomMediaBuilder $mediaBuilder;

    /** @var GedcomFileHandler Handles file operations and downloads */
    private GedcomFileHandler $fileHandler;

    /** @var GedcomFormatter Common formatting utilities */
    private GedcomFormatter $formatter;

    // --------------------------------------------------------------------------------------
    // CONSTRUCTOR & INITIALIZATION
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

        $this->initializeBuilders();
    }

    /**
     * Clean up temporary files on destruction.
     */
    public function __destruct()
    {
        $this->fileHandler->cleanup();
    }

    // --------------------------------------------------------------------------------------
    // PUBLIC API - Main Export Methods
    // --------------------------------------------------------------------------------------

    /**
     * Export genealogical data to GEDCOM format.
     *
     * This is the main entry point for generating GEDCOM content.
     * Delegates to specialized builders for each record type.
     *
     * @param  Collection<Person>  $individuals  Collection of Person models
     * @param  Collection  $couples  Collection of couple models
     * @return string Complete GEDCOM content
     */
    public function export(Collection $individuals, Collection $couples): string
    {
        // Collect media objects for individuals
        $this->mediaBuilder->collectMediaObjects($individuals);

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
        return $this->fileHandler->downloadGedcom($gedcom);
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
        return $this->fileHandler->downloadZip($gedcom, $this->mediaBuilder->getMediaFiles());
    }

    /**
     * Initialize all specialized builders with their dependencies.
     */
    private function initializeBuilders(): void
    {
        $this->formatter         = new GedcomFormatter();
        $this->headerBuilder     = new GedcomHeaderBuilder($this->teamname, self::GEDCOM_VERSION, $this->formatter);
        $this->individualBuilder = new GedcomIndividualBuilder($this->formatter);
        $this->familyBuilder     = new GedcomFamilyBuilder($this->formatter);
        $this->mediaBuilder      = new GedcomMediaBuilder($this->format, $this->formatter);
        $this->fileHandler       = new GedcomFileHandler($this->basename, $this->format, $this->filename);
    }

    // --------------------------------------------------------------------------------------
    // GEDCOM BUILDING - Core Structure Orchestration
    // --------------------------------------------------------------------------------------

    /**
     * Build complete GEDCOM content using specialized builders.
     *
     * Coordinates the export process by delegating to appropriate builders
     * while maintaining the proper GEDCOM structure and record order.
     *
     * @param  Collection<Person>  $individuals
     * @return string Complete GEDCOM content
     */
    private function buildGedcom(Collection $individuals, Collection $couples): string
    {
        $submitter   = $this->getSubmitter();
        $submitterId = $submitter ? "@I{$submitter->id}@" : '@SUB1@';

        // Build family structures for GEDCOM
        $gedcomFamilies = $this->familyBuilder->buildGedcomFamilies($individuals, $couples);
        $famsMapping    = $this->familyBuilder->buildFamilyMapping($gedcomFamilies);

        $gedcom = '';
        $gedcom .= $this->headerBuilder->buildHeader($submitterId);
        $gedcom .= $this->headerBuilder->buildSubmitterRecord($submitter);
        $gedcom .= $this->individualBuilder->buildIndividuals($individuals, $famsMapping, $this->familyBuilder, $this->mediaBuilder);
        $gedcom .= $this->familyBuilder->buildFamilies($gedcomFamilies);
        $gedcom .= $this->mediaBuilder->buildMediaRecords();
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
     * Build GEDCOM footer/trailer record.
     *
     * @return string GEDCOM trailer content
     */
    private function buildFooter(): string
    {
        return '0 TRLR' . $this->formatter->eol();
    }

    // --------------------------------------------------------------------------------------
    // CONFIGURATION METHODS
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
}
