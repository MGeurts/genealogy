<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

use App\Models\Person;
use App\Models\Team;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Family importer - handles importing families and setting parent-child relationships
 */
class FamilyImporter
{
    /** @phpstan-ignore property.onlyWritten */
    private Team $team;

    /** @var array<string, array{husband: ?string, wife: ?string, children: array<string>, marriage_date: ?string, divorce_date: ?string}> */
    private array $familyMap = [];

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Import families and set parent-child relationships
     *
     * @param  array<string, array{id: string, type: string, data: array<mixed>}|null>  $families
     * @param  array<string, int>  $personMap
     * @return array<string, array{husband: ?string, wife: ?string, children: array<string>, marriage_date: ?string, divorce_date: ?string}>
     */
    public function import(array $families, array $personMap): array
    {
        foreach ($families as $gedcomId => $family) {
            $familyData = $this->extractFamilyData($family);

            // Update children with parent information
            if (! empty($familyData['children'])) {
                foreach ($familyData['children'] as $childGedcomId) {
                    if (isset($personMap[$childGedcomId])) {
                        $updateData = [];

                        if ($familyData['husband'] && isset($personMap[$familyData['husband']])) {
                            $updateData['father_id'] = $personMap[$familyData['husband']];
                        }

                        if ($familyData['wife'] && isset($personMap[$familyData['wife']])) {
                            $updateData['mother_id'] = $personMap[$familyData['wife']];
                        }

                        if (! empty($updateData)) {
                            Person::where('id', $personMap[$childGedcomId])->update($updateData);
                        }
                    }
                }
            }

            $this->familyMap[$gedcomId] = $familyData;
        }

        return $this->familyMap;
    }

    /**
     * Get the family mapping
     *
     * @return array<string, array{husband: ?string, wife: ?string, children: array<string>, marriage_date: ?string, divorce_date: ?string}>
     */
    public function getFamilyMap(): array
    {
        return $this->familyMap;
    }

    /**
     * Extract family data from GEDCOM family record
     *
     * @param  array{id: string, type: string, data: array<mixed>}|null  $family
     * @return array{husband: ?string, wife: ?string, children: array<string>, marriage_date: ?string, divorce_date: ?string}
     */
    private function extractFamilyData(?array $family): array
    {
        $data = [
            'husband'       => null,
            'wife'          => null,
            'children'      => [],
            'marriage_date' => null,
            'divorce_date'  => null,
        ];

        // Handle null family or missing data gracefully
        if ($family === null || ! isset($family['data']) || ! is_array($family['data'])) {
            Log::warning('GEDCOM Import: Invalid family data structure', [
                'family' => $family,
            ]);

            return $data;
        }

        foreach ($family['data'] as $field) {
            // Add safety check for field structure
            if (! is_array($field) || ! isset($field['tag'])) {
                continue;
            }

            switch ($field['tag']) {
                case 'HUSB':
                    $data['husband'] = mb_trim($field['value'] ?? '', '@');
                    break;

                case 'WIFE':
                    $data['wife'] = mb_trim($field['value'] ?? '', '@');
                    break;

                case 'CHIL':
                    $childId = mb_trim($field['value'] ?? '', '@');
                    if (! empty($childId)) {
                        $data['children'][] = $childId;
                    }
                    break;

                case 'MARR':
                    $marriageInfo          = $this->extractEvent($field);
                    $data['marriage_date'] = $marriageInfo['date'];
                    break;

                case 'DIV':
                    $divorceInfo          = $this->extractEvent($field);
                    $data['divorce_date'] = $divorceInfo['date'];
                    break;
            }
        }

        return $data;
    }

    /**
     * Extract event data (birth, death, etc.)
     *
     * @param  array<string, mixed>  $eventField
     * @return array{date: ?string, year: ?int, place: ?string}
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
     *
     * @return array{date: ?string, year: ?int}
     */
    private function parseDate(string $dateString): array
    {
        $result = ['date' => null, 'year' => null];

        // Remove common prefixes
        $dateString = preg_replace('/^(ABT|EST|CAL|AFT|BEF|BET)\s+/i', '', mb_trim($dateString));

        // preg_replace can return null on error, so we need to handle that
        if ($dateString === null) {
            return $result;
        }

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
}
