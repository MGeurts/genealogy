<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

use App\Models\Person;
use App\Models\PersonMetadata;
use App\Models\Team;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Individual importer - handles importing persons from GEDCOM data
 */
class IndividualImporter
{
    private Team $team;

    private array $personMap = [];

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Import individuals from parsed GEDCOM data
     *
     * @param  array  $individuals  Parsed individual records
     * @param  MediaImportHandler|null  $mediaHandler  Optional media handler for extracting references
     * @return array Mapping of GEDCOM ID to Person database ID
     */
    public function import(array $individuals, ?MediaImportHandler $mediaHandler = null): array
    {
        foreach ($individuals as $gedcomId => $individual) {
            if ($individual === null) {
                continue;
            }

            // Extract media references if handler is provided
            if ($mediaHandler) {
                $mediaHandler->extractMediaReferences($gedcomId, $individual);
            }

            $personData = $this->extractPersonData($individual);

            // Truncate fields to prevent database errors
            $personData = $this->truncatePersonData($personData);

            $person = Person::create([
                'firstname' => $personData['firstname'],
                'surname'   => $personData['surname'] ?? '????',
                'birthname' => $personData['birthname'],
                'nickname'  => $personData['nickname'],
                'sex'       => $personData['sex'],
                'dob'       => $personData['dob'],
                'yob'       => $personData['yob'],
                'pob'       => $personData['pob'],
                'dod'       => $personData['dod'],
                'yod'       => $personData['yod'],
                'pod'       => $personData['pod'],
                'summary'   => $personData['summary'],
                'team_id'   => $this->team->id,
            ]);

            $this->personMap[$gedcomId] = $person->id;

            // Store additional metadata
            if (! empty($personData['metadata'])) {
                foreach ($personData['metadata'] as $key => $value) {
                    PersonMetadata::create([
                        'person_id' => $person->id,
                        'key'       => $key,
                        'value'     => $value,
                    ]);
                }
            }
        }

        return $this->personMap;
    }

    /**
     * Get the person mapping
     */
    public function getPersonMap(): array
    {
        return $this->personMap;
    }

    /**
     * Extract person data from GEDCOM individual record
     */
    private function extractPersonData(?array $individual): array
    {
        $data = [
            'firstname' => null,
            'surname'   => null,
            'birthname' => null,
            'nickname'  => null,
            'sex'       => 'm',
            'dob'       => null,
            'yob'       => null,
            'pob'       => null,
            'dod'       => null,
            'yod'       => null,
            'pod'       => null,
            'summary'   => null,
            'metadata'  => [],
        ];

        if ($individual === null || ! isset($individual['data'])) {
            return $data;
        }

        $hasBurial = false;

        foreach ($individual['data'] as $field) {
            switch ($field['tag']) {
                case 'NAME':
                    $nameInfo          = $this->parseName($field['value']);
                    $data['firstname'] = $nameInfo['given'];
                    $data['surname']   = $nameInfo['surname'];
                    $data['birthname'] = $nameInfo['surname']; // Default birthname to surname
                    break;

                case 'SEX':
                    $data['sex'] = mb_strtolower($field['value']) === 'f' ? 'f' : 'm';
                    break;

                case 'BIRT':
                    $birthInfo   = $this->extractEvent($field);
                    $data['dob'] = $birthInfo['date'];
                    $data['yob'] = $birthInfo['year'];
                    $data['pob'] = $birthInfo['place'];
                    break;

                case 'DEAT':
                    $deathInfo   = $this->extractEvent($field);
                    $data['dod'] = $deathInfo['date'];
                    $data['yod'] = $deathInfo['year'];
                    $data['pod'] = $deathInfo['place'];
                    break;

                case 'BURI':
                    $cemeteryName    = null;
                    $cemeteryAddress = null;
                    $latitude        = null;
                    $longitude       = null;

                    if (isset($field['data'])) {
                        foreach ($field['data'] as $buriField) {
                            switch ($buriField['tag']) {
                                case 'PLAC':
                                    // Modern export: PLAC = cemetery name
                                    // Legacy export: PLAC = "Name, Address"
                                    if (mb_strpos($buriField['value'], ',') !== false) {
                                        $segments        = array_map('trim', explode(',', $buriField['value'], 2));
                                        $cemeteryName    = $segments[0];
                                        $cemeteryAddress = $segments[1] ?? null;
                                    } else {
                                        $cemeteryName = $buriField['value'];
                                    }
                                    break;

                                case 'ADDR':
                                    // Start with first line
                                    $cemeteryAddress = $buriField['value'];

                                    // Collect CONT lines (additional address lines)
                                    if (isset($buriField['data'])) {
                                        foreach ($buriField['data'] as $addrField) {
                                            if ($addrField['tag'] === 'CONT') {
                                                $cemeteryAddress .= PHP_EOL . $addrField['value'];
                                            }
                                        }
                                    }
                                    break;

                                case 'MAP':
                                    if (isset($buriField['data'])) {
                                        foreach ($buriField['data'] as $mapField) {
                                            if ($mapField['tag'] === 'LATI') {
                                                $latitude = $mapField['value'];
                                            }
                                            if ($mapField['tag'] === 'LONG') {
                                                $longitude = $mapField['value'];
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }

                    if ($cemeteryName) {
                        $data['metadata']['cemetery_location_name'] = $cemeteryName;
                    }
                    if ($cemeteryAddress) {
                        $data['metadata']['cemetery_location_address'] = $cemeteryAddress;
                    }
                    if ($latitude) {
                        $data['metadata']['cemetery_location_latitude'] = $latitude;
                    }
                    if ($longitude) {
                        $data['metadata']['cemetery_location_longitude'] = $longitude;
                    }

                    $hasBurial = true;
                    break;

                case 'NOTE':
                    $note = $field['value'] ?? '';

                    // Append CONT / CONC lines if present
                    if (isset($field['data'])) {
                        foreach ($field['data'] as $noteField) {
                            if ($noteField['tag'] === 'CONC') {
                                $note .= $noteField['value'];
                            } elseif ($noteField['tag'] === 'CONT') {
                                $note .= PHP_EOL . $noteField['value'];
                            }
                        }
                    }

                    $data['summary'] = $this->concatenateNotes($data['summary'], $note);
                    break;

                case 'OCCU':
                    $occupation = $field['value'] ?? '';
                    if (isset($field['data'])) {
                        foreach ($field['data'] as $sub) {
                            if ($sub['tag'] === 'CONC') {
                                $occupation .= $sub['value'];
                            } elseif ($sub['tag'] === 'CONT') {
                                $occupation .= PHP_EOL . $sub['value'];
                            }
                        }
                    }
                    $data['metadata']['occupation'] = $occupation;
                    break;

                case 'RELI':
                    $data['metadata']['religion'] = $field['value'];
                    break;

                case 'EDUC':
                    $education = $field['value'] ?? '';
                    if (isset($field['data'])) {
                        foreach ($field['data'] as $sub) {
                            if ($sub['tag'] === 'CONC') {
                                $education .= $sub['value'];
                            } elseif ($sub['tag'] === 'CONT') {
                                $education .= PHP_EOL . $sub['value'];
                            }
                        }
                    }
                    $data['metadata']['education'] = $education;
                    break;

                    // Note: OBJE tags are handled by MediaImportHandler
                    // We skip them here to avoid duplication
            }

            // Handle nickname from NAME variations
            if ($field['tag'] === 'NAME' && isset($field['data'])) {
                foreach ($field['data'] as $nameField) {
                    if ($nameField['tag'] === 'NICK') {
                        $data['nickname'] = $nameField['value'];
                    }
                }
            }
        }

        // Fallback: if no BURI but death place exists → use as cemetery address
        if (! $hasBurial && ! empty($data['pod'])) {
            $data['metadata']['cemetery_location_address'] = $data['pod'];
        }

        return $data;
    }

    /**
     * Parse GEDCOM name format
     */
    private function parseName(string $name): array
    {
        $result = ['given' => null, 'surname' => null];

        // GEDCOM format: Given names /Surname/
        if (preg_match('/^(.*?)\s*\/([^\/]*)\/?/', $name, $matches)) {
            $result['given']   = mb_trim($matches[1]) ?: null;
            $result['surname'] = mb_trim($matches[2]) ?: null;
        } else {
            // No surname markers, treat as given name
            $result['given'] = mb_trim($name) ?: null;
        }

        return $result;
    }

    /**
     * Extract event data (birth, death, etc.)
     */
    private function extractEvent(array $eventField): array
    {
        $result = ['date' => null, 'year' => null, 'place' => null];

        if (isset($eventField['data'])) {
            foreach ($eventField['data'] as $subField) {
                switch ($subField['tag']) {
                    case 'DATE':
                        $dateInfo       = $this->parseDate($subField['value']);
                        $result['date'] = $dateInfo['date'];
                        $result['year'] = $dateInfo['year'];
                        break;

                    case 'PLAC':
                        $result['place'] = $subField['value'];
                        break;
                }
            }
        }

        return $result;
    }

    /**
     * Parse GEDCOM date formats
     */
    private function parseDate(string $dateString): array
    {
        $result = ['date' => null, 'year' => null];

        // Remove common prefixes
        $dateString = preg_replace('/^(ABT|EST|CAL|AFT|BEF|BET)\s+/i', '', mb_trim($dateString));

        // Extract year
        if (preg_match('/\b(\d{4})\b/', $dateString, $matches)) {
            $result['year'] = (int) $matches[1];
        }

        // Try to parse full date
        try {
            // Handle various GEDCOM date formats
            if (preg_match('/^(\d{1,2})\s+(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)\s+(\d{4})$/i', $dateString, $matches)) {
                $months = [
                    'JAN' => 1, 'FEB' => 2, 'MAR' => 3, 'APR' => 4,
                    'MAY' => 5, 'JUN' => 6, 'JUL' => 7, 'AUG' => 8,
                    'SEP' => 9, 'OCT' => 10, 'NOV' => 11, 'DEC' => 12,
                ];

                $day   = (int) $matches[1];
                $month = $months[mb_strtoupper($matches[2])];
                $year  = (int) $matches[3];

                $result['date'] = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
            }
        } catch (Exception $e) {
            // If parsing fails, keep only the year
        }

        return $result;
    }

    /**
     * Concatenate notes
     */
    private function concatenateNotes(?string $existing, string $new): string
    {
        if (empty($existing)) {
            return $new;
        }

        return $existing . "\n\n" . $new;
    }

    /**
     * Truncate person data to fit database column limits
     */
    private function truncatePersonData(array $personData): array
    {
        // Define column limits (adjust these based on your database schema)
        $limits = [
            'firstname' => 255,
            'surname'   => 255,
            'birthname' => 255,
            'nickname'  => 255,
            'pob'       => 255,
            'pod'       => 255,
        ];

        foreach ($limits as $field => $limit) {
            if (! empty($personData[$field]) && mb_strlen($personData[$field]) > $limit) {
                $personData[$field] = mb_substr($personData[$field], 0, $limit);

                // Log truncation for awareness
                Log::info("GEDCOM Import: Truncated {$field}", [
                    'original_length' => mb_strlen($personData[$field] ?? ''),
                    'truncated_to'    => $limit,
                ]);
            }
        }

        return $personData;
    }
}
