<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;

// ==============================================================================
// GEDCOM INDIVIDUAL BUILDER - Handles person records
// ==============================================================================

/**
 * GEDCOM Individual Builder Class
 *
 * Specializes in building individual person records with support for:
 * - Name variations (birth names, nicknames)
 * - Birth and death information
 * - Cemetery and burial details with coordinates
 * - Family relationships (FAMC/FAMS)
 * - Media object references
 * - Personal notes and summaries
 */
class GedcomIndividualBuilder
{
    /**
     * Create individual builder instance.
     *
     * @param  GedcomFormatter  $formatter  Text formatter instance
     */
    public function __construct(private GedcomFormatter $formatter) {}

    // --------------------------------------------------------------------------------------
    // MAIN BUILDING METHODS
    // --------------------------------------------------------------------------------------

    /**
     * Build all individual records.
     *
     * Processes the collection of individuals and generates complete
     * GEDCOM individual records for each person.
     *
     * @param  Collection<Person>  $individuals  Collection of Person models
     * @param  array<int, array<int>>  $famsMapping  Person ID to family IDs mapping
     * @param  GedcomFamilyBuilder  $familyBuilder  Family builder for relationship queries
     * @param  GedcomMediaBuilder  $mediaBuilder  Media builder for photo references
     * @return string All individual records
     */
    public function buildIndividuals(
        Collection $individuals,
        array $famsMapping,
        GedcomFamilyBuilder $familyBuilder,
        GedcomMediaBuilder $mediaBuilder
    ): string {
        $gedcom = '';
        foreach ($individuals as $person) {
            $gedcom .= $this->buildIndividualRecord($person, $famsMapping, $familyBuilder, $mediaBuilder);
        }

        return $gedcom;
    }

    /**
     * Build a single individual record.
     *
     * Creates a complete GEDCOM individual record with all available
     * information including names, vital events, relationships, and media.
     *
     * @param  Person  $person  Person model instance
     * @param  array<int, array<int>>  $famsMapping  Family mapping data
     * @param  GedcomFamilyBuilder  $familyBuilder  Family builder instance
     * @param  GedcomMediaBuilder  $mediaBuilder  Media builder instance
     * @return string Individual GEDCOM record
     */
    private function buildIndividualRecord(
        Person $person,
        array $famsMapping,
        GedcomFamilyBuilder $familyBuilder,
        GedcomMediaBuilder $mediaBuilder
    ): string {
        $lines   = [];
        $lines[] = "0 @I{$person->id}@ INDI";

        // Core information
        $lines = array_merge($lines, $this->buildNameFields($person));
        $lines = array_merge($lines, $this->buildSexField($person));
        $lines = array_merge($lines, $this->buildBirthFields($person));
        $lines = array_merge($lines, $this->buildDeathFields($person));
        $lines = array_merge($lines, $this->buildNoteFields($person));

        // Family relationships
        $lines = array_merge($lines, $this->buildFamilyRelationships($person, $famsMapping, $familyBuilder));

        // Media objects (photos)
        $lines = array_merge($lines, $mediaBuilder->buildIndividualMediaFields($person));

        // Additional fields - override to add more
        $lines = array_merge($lines, $this->buildAdditionalIndividualFields($person));

        return implode($this->formatter->eol(), $lines) . $this->formatter->eol();
    }

    // --------------------------------------------------------------------------------------
    // NAME FIELD BUILDING
    // --------------------------------------------------------------------------------------

    /**
     * Build name fields for an individual.
     *
     * Handles primary names, birth names, and nicknames according to
     * GEDCOM 7.0 name structure requirements.
     *
     * @param  Person  $person  Person model instance
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
            $lines[] = '2 NICK ' . $this->formatter->oneLine((string) $person->nickname);
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

    // --------------------------------------------------------------------------------------
    // BASIC INFORMATION FIELDS
    // --------------------------------------------------------------------------------------

    /**
     * Build sex field for an individual.
     *
     * @param  Person  $person  Person model instance
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

    // --------------------------------------------------------------------------------------
    // VITAL EVENT FIELDS
    // --------------------------------------------------------------------------------------

    /**
     * Build birth-related fields for an individual.
     *
     * Handles both full birth dates and year-only birth information,
     * plus place of birth details.
     *
     * @param  Person  $person  Person model instance
     * @return array<string> Birth field lines
     */
    private function buildBirthFields(Person $person): array
    {
        $lines = [];
        if ($person->dob || $person->yob || $person->pob) {
            $lines[] = '1 BIRT';

            // Full date of birth
            if ($person->dob) {
                if ($d = $this->formatter->formatGedcomDate($person->dob)) {
                    $lines[] = "2 DATE {$d}";
                }
            }
            // Fallback to year of birth if no full date
            elseif ($person->yob) {
                $lines[] = '2 DATE ' . (int) $person->yob;
            }

            // Place of birth
            if ($person->pob) {
                $lines[] = '2 PLAC ' . $this->formatter->sanitizeText((string) $person->pob);
            }
        }

        return $lines;
    }

    /**
     * Build death-related fields for an individual.
     *
     * Includes death dates, places, and cemetery/burial information
     * with coordinate support for cemetery locations.
     *
     * @param  Person  $person  Person model instance
     * @return array<string> Death field lines
     */
    private function buildDeathFields(Person $person): array
    {
        $lines = [];
        if ($person->dod || $person->yod || $person->pod) {
            $lines[] = '1 DEAT';

            // Full date of death
            if ($person->dod && $d = $this->formatter->formatGedcomDate($person->dod)) {
                $lines[] = "2 DATE {$d}";
            } elseif ($person->yod) {
                $lines[] = '2 DATE ' . (int) $person->yod;
            }

            // Place of death
            if ($person->pod) {
                $lines[] = '2 PLAC ' . $this->formatter->sanitizeText((string) $person->pod);
            }

            // Cemetery - Fixed MAP tag placement and coordinate format
            $lines = array_merge($lines, $this->buildCemeteryFields($person));
        }

        return $lines;
    }

    /**
     * Build cemetery burial fields for an individual.
     *
     * Handles cemetery name, address, and GPS coordinates according to
     * GEDCOM 7.0 MAP structure requirements.
     *
     * @param  Person  $person  Person model instance
     * @return array<string> Cemetery field lines
     */
    private function buildCemeteryFields(Person $person): array
    {
        $cemetery_name               = $person->getMetadataValue('cemetery_location_name');
        $cemetery_address            = $person->getMetadataValue('cemetery_location_address');
        $cemetery_location_latitude  = $person->getMetadataValue('cemetery_location_latitude');
        $cemetery_location_longitude = $person->getMetadataValue('cemetery_location_longitude');

        if (! ($cemetery_name || $cemetery_address || $cemetery_location_latitude || $cemetery_location_longitude)) {
            return [];
        }

        $lines = ['1 BURI'];

        // Build cemetery place name with coordinates
        $cemeteryPlace = $cemetery_name ?: '';

        if ($cemeteryPlace) {
            $lines[] = '2 PLAC ' . $this->formatter->sanitizeText($cemeteryPlace);

            // Add map coordinates under PLAC (GEDCOM 7.0 compliant format)
            if ($cemetery_location_latitude || $cemetery_location_longitude) {
                $lines[] = '3 MAP';
                if ($cemetery_location_latitude) {
                    $lines[] = '4 LATI ' . $this->formatter->formatGedcomCoordinate($cemetery_location_latitude, 'latitude');
                }
                if ($cemetery_location_longitude) {
                    $lines[] = '4 LONG ' . $this->formatter->formatGedcomCoordinate($cemetery_location_longitude, 'longitude');
                }
            }
        }

        // Cemetery address (ADDR) with multi-line support
        if ($cemetery_address) {
            $lines = array_merge(
                $lines,
                $this->formatter->exportMultilineText('ADDR', $cemetery_address, 2)
            );
        }

        return $lines;
    }

    // --------------------------------------------------------------------------------------
    // NOTE AND RELATIONSHIP FIELDS
    // --------------------------------------------------------------------------------------

    /**
     * Build note-related fields for an individual.
     *
     * Handles multi-line personal summaries and notes with proper
     * CONT/CONC line handling.
     *
     * @param  Person  $person  Person model instance
     * @return array<string> Note field lines
     */
    private function buildNoteFields(Person $person): array
    {
        $lines = [];
        if (! empty($person->summary)) {
            $lines = array_merge($lines, $this->formatter->exportMultilineText('NOTE', $person->summary, 1));
        }

        return $lines;
    }

    /**
     * Build family relationship fields for an individual.
     *
     * Creates FAMC (child in family) and FAMS (spouse in family) references
     * based on the person's relationships and family structure.
     *
     * @param  Person  $person  Person model instance
     * @param  array<int, array<int>>  $famsMapping  Family mapping data
     * @param  GedcomFamilyBuilder  $familyBuilder  Family builder for queries
     * @return array<string> Family relationship field lines
     */
    private function buildFamilyRelationships(Person $person, array $famsMapping, GedcomFamilyBuilder $familyBuilder): array
    {
        $lines = [];

        // Child in family (FAMC) - find parent family
        $parentFamilyId = $familyBuilder->getPersonParentFamilyId($person);
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
     * Build additional individual fields.
     *
     * Override this method to add custom fields like:
     * - OCCU (Occupation)
     * - RELI (Religion)
     * - SOUR (Sources)
     * - Custom events and attributes
     *
     * @param  Person  $person  Person model instance
     * @return array<string> Additional field lines
     */
    private function buildAdditionalIndividualFields(Person $person): array
    {
        return [];
    }
}
