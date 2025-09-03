<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

use App\Models\Couple;
use App\Models\Person;
use App\Models\PersonMetadata;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;

final class ImportOriginal implements CreatesTeams
{
    public User $user;

    private Team $team;

    private array $gedcomData = [];

    private array $individuals = [];

    private array $families = [];

    private array $personMap = [];

    private array $familyMap = [];

    /**
     * Initialize with user and create a new team
     */
    public function __construct(?string $teamName, ?string $teamDescription)
    {
        $this->user = auth()->user();

        // Create new team for this import
        $this->team = $this->createTeam($teamName, $teamDescription);
    }

    /**
     * Import GEDCOM file content
     */
    public function import(string $gedcomContent): array
    {
        // At the start of your import method, increase time and memory limits
        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('memory_limit', '512M');

        try {
            DB::beginTransaction();

            // Parse GEDCOM content
            $this->parseGedcom($gedcomContent);

            Log::info('GEDCOM IMPORT: parseGedcom', ['gedcomData' => $this->gedcomData]);

            // Import individuals first
            $this->importIndividuals();

            Log::info('GEDCOM IMPORT: importIndividuals', ['individuals' => $this->individuals, 'personMap' => $this->personMap]);

            // Import families and relationships
            $this->importFamilies();

            Log::info('GEDCOM IMPORT: importFamilies', ['families' => $this->families, 'familyMap' => $this->familyMap]);

            // Create couples from families
            $this->createCouples();

            Log::info('GEDCOM IMPORT: createCouples', ['data' => '????']);

            DB::commit();

            return [
                'success'              => true,
                'team'                 => $this->team->name,
                'individuals_imported' => count($this->personMap),
                'families_imported'    => count($this->familyMap),
                'message'              => 'GEDCOM file imported successfully',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('GEDCOM Import Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Get import statistics
     */
    public function getStatistics(): array
    {
        return [
            'individuals_parsed'   => count($this->individuals),
            'families_parsed'      => count($this->families),
            'individuals_imported' => count($this->personMap),
            'families_imported'    => count($this->familyMap),
        ];
    }

    /**
     * Create a new team for the import
     */
    private function createTeam(string $name, ?string $description): Team
    {
        AddingTeam::dispatch($this->user);

        $this->user->switchTeam($team = $this->user->ownedTeams()->create([
            'name'          => $name,
            'description'   => $description ?? null,
            'personal_team' => false,
        ]));

        // -----------------------------------------------------------------------
        // create team photo folder
        // -----------------------------------------------------------------------
        if (! Storage::disk('photos')->exists($team->id)) {
            Storage::disk('photos')->makeDirectory($team->id);
        }
        // -----------------------------------------------------------------------

        return $team;
    }

    /**
     * Parse GEDCOM content into structured data
     */
    private function parseGedcom(string $content): void
    {
        $lines         = explode("\n", str_replace(["\r\n", "\r"], "\n", $content));
        $currentRecord = null;

        // Add progress tracking
        $totalLines     = mb_substr_count($content, "\n");
        $processedLines = 0;

        foreach ($lines as $lineNumber => $line) {
            // Progress logging
            if ($processedLines % 1000 === 0) {
                Log::debug("GEDCOM parsing progress: {$processedLines}/{$totalLines} lines");
            }
            $processedLines++;

            $line = mb_trim($line);
            if (empty($line)) {
                continue;
            }

            // Parse GEDCOM line more efficiently
            $parts = explode(' ', $line, 4);
            if (count($parts) < 2) {
                continue;
            }

            $level = (int) $parts[0];

            // Handle level 0 records (new records)
            if ($level === 0) {
                if (count($parts) >= 3) {
                    $possibleId = $parts[1];
                    $tag        = $parts[2];
                    $value      = $parts[3] ?? '';

                    // Check if this is an ID record (starts and ends with @)
                    if (str_starts_with($possibleId, '@') && str_ends_with($possibleId, '@')) {
                        $id = mb_trim($possibleId, '@');

                        if ($tag === 'INDI') {
                            $this->individuals[$id] = [
                                'id'   => $id,
                                'type' => 'INDI',
                                'data' => [],
                            ];
                            $currentRecord   = &$this->individuals[$id];
                            $currentRecordId = $id;
                        } elseif ($tag === 'FAM') {
                            $this->families[$id] = [
                                'id'   => $id,
                                'type' => 'FAM',
                                'data' => [],
                            ];
                            $currentRecord   = &$this->families[$id];
                            $currentRecordId = $id;
                        } else {
                            // Other ID records (SOUR, OBJE, etc.)
                            $currentRecord   = null;
                            $currentRecordId = null;
                        }
                    } else {
                        // Non-ID level 0 records (HEAD, TRLR)
                        $tag                = $parts[1];
                        $value              = $parts[2] ?? '';
                        $this->gedcomData[] = ['type' => $tag, 'value' => mb_trim($value)];
                        $currentRecord      = null;
                        $currentRecordId    = null;
                    }
                }
            } elseif ($level === 1 && $currentRecord !== null && isset($currentRecord['data'])) {
                // Level 1 data for current record - add additional safety check
                $tag   = $parts[1];
                $value = implode(' ', array_slice($parts, 2));

                $currentRecord['data'][] = [
                    'tag'   => $tag,
                    'value' => mb_trim($value),
                    'level' => 1,
                    'data'  => [],
                ];
            } elseif ($level === 2 && $currentRecord !== null &&
                      isset($currentRecord['data']) && ! empty($currentRecord['data'])) {
                // Level 2 data - add to the last level 1 item
                $lastIndex = count($currentRecord['data']) - 1;
                if ($lastIndex >= 0) {
                    $tag   = $parts[1];
                    $value = implode(' ', array_slice($parts, 2));

                    // Ensure the data array exists
                    if (! isset($currentRecord['data'][$lastIndex]['data'])) {
                        $currentRecord['data'][$lastIndex]['data'] = [];
                    }

                    $currentRecord['data'][$lastIndex]['data'][] = [
                        'tag'   => $tag,
                        'value' => mb_trim($value),
                        'level' => 2,
                    ];
                }
            }
            // For higher levels (3+) or when no current record, we skip for now
            // This simplified approach handles the most common GEDCOM structures
        }

        // Remove references to avoid memory issues
        unset($currentRecord);

        // Debug logging to help identify the issue
        Log::info('GEDCOM Parse Complete', [
            'individuals_count' => count($this->individuals),
            'families_count'    => count($this->families),
            'families_keys'     => array_keys($this->families),
        ]);
    }

    /**
     * Import individuals from parsed GEDCOM data
     */
    private function importIndividuals(): void
    {
        foreach ($this->individuals as $gedcomId => $individual) {
            $personData = $this->extractPersonData($individual);

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
    }

    /**
     * Extract person data from GEDCOM individual record
     */
    private function extractPersonData(array $individual): array
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

        if (! isset($individual['data'])) {
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
     * Import families and set parent-child relationships
     */
    private function importFamilies(): void
    {
        foreach ($this->families as $gedcomId => $family) {
            $familyData = $this->extractFamilyData($family);

            // Update children with parent information
            if (! empty($familyData['children'])) {
                foreach ($familyData['children'] as $childGedcomId) {
                    if (isset($this->personMap[$childGedcomId])) {
                        $updateData = [];

                        if ($familyData['husband'] && isset($this->personMap[$familyData['husband']])) {
                            $updateData['father_id'] = $this->personMap[$familyData['husband']];
                        }

                        if ($familyData['wife'] && isset($this->personMap[$familyData['wife']])) {
                            $updateData['mother_id'] = $this->personMap[$familyData['wife']];
                        }

                        if (! empty($updateData)) {
                            Person::where('id', $this->personMap[$childGedcomId])->update($updateData);
                        }
                    }
                }
            }

            $this->familyMap[$gedcomId] = $familyData;
        }
    }

    /**
     * Extract family data from GEDCOM family record
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
     * Create couple records from family data
     */
    private function createCouples(): void
    {
        foreach ($this->familyMap as $gedcomId => $familyData) {
            if ($familyData['husband'] && $familyData['wife'] &&
                isset($this->personMap[$familyData['husband']]) &&
                isset($this->personMap[$familyData['wife']])) {
                $person1Id = $this->personMap[$familyData['husband']];
                $person2Id = $this->personMap[$familyData['wife']];

                // Ensure person1_id < person2_id for consistency
                if ($person1Id > $person2Id) {
                    [$person1Id, $person2Id] = [$person2Id, $person1Id];
                }

                $coupleData = [
                    'person1_id' => $person1Id,
                    'person2_id' => $person2Id,
                    'date_start' => $familyData['marriage_date'],
                    'date_end'   => $familyData['divorce_date'],
                    'is_married' => ! empty($familyData['marriage_date']),
                    'has_ended'  => ! empty($familyData['divorce_date']),
                    'team_id'    => $this->team->id,
                ];

                try {
                    Couple::create($coupleData);
                } catch (Exception $e) {
                    // Handle duplicate couples gracefully
                    Log::warning('Duplicate couple record skipped', [
                        'person1_id' => $person1Id,
                        'person2_id' => $person2Id,
                        'error'      => $e->getMessage(),
                    ]);
                }
            }
        }
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
}
