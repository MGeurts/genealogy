<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use App\Models\User;

// ==============================================================================
// GEDCOM HEADER BUILDER - Handles headers and submitter records
// ==============================================================================

/**
 * GEDCOM Header Builder Class
 *
 * Responsible for building GEDCOM headers and submitter records
 * according to GEDCOM 7.0 specifications.
 */
class GedcomHeaderBuilder
{
    /**
     * Create header builder instance.
     *
     * @param  string  $teamname  Team name for GEDCOM
     * @param  string  $gedcomVersion  GEDCOM version specification
     * @param  GedcomFormatter  $formatter  Text formatter instance
     */
    public function __construct(
        private string $teamname,
        private string $gedcomVersion,
        private GedcomFormatter $formatter
    ) {}

    // --------------------------------------------------------------------------------------
    // HEADER BUILDING
    // --------------------------------------------------------------------------------------

    /**
     * Build GEDCOM header record.
     *
     * Creates a compliant GEDCOM 7.0 header with source information,
     * version details, and submission metadata.
     *
     * @param  string  $submitterId  Reference to submitter record
     * @return string GEDCOM header content
     */
    public function buildHeader(string $submitterId): string
    {
        $now = now();

        $headerLines = [
            '0 HEAD',
            '1 GEDC',
            '2 VERS ' . $this->gedcomVersion,
            '1 SOUR ' . $this->getSourceName(),
            '2 VERS 1.0',
            '2 NAME ' . $this->teamname,
            '2 CORP ' . $this->getSourceCorporation(),
            '1 DATE ' . mb_strtoupper($now->format('j M Y')),
            '2 TIME ' . $now->format('H:i:s'),
            "1 SUBM {$submitterId}",
            '1 LANG ' . app()->getLocale(),
        ];

        return implode($this->formatter->eol(), $headerLines) . $this->formatter->eol();
    }

    /**
     * Build submitter record.
     *
     * Creates a GEDCOM submitter record for the person exporting the data.
     *
     * @param  User|null  $submitter  User submitting the GEDCOM
     * @return string GEDCOM submitter record
     */
    public function buildSubmitterRecord(?User $submitter): string
    {
        if (! $submitter) {
            return '0 @SUB1@ SUBM' . $this->formatter->eol() .
                   '1 NAME Unknown' . $this->formatter->eol();
        }

        $submitterId = "@I{$submitter->id}@";
        $name        = mb_trim(($submitter->firstname ?? '') . ' ' . ($submitter->surname ?? ''));

        return "0 {$submitterId} SUBM" . $this->formatter->eol() .
               "1 NAME {$name}" . $this->formatter->eol();
    }

    // --------------------------------------------------------------------------------------
    // CONFIGURATION METHODS
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
}
