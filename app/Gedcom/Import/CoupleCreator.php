<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

use App\Models\Couple;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Couple creator - handles creating couple records from family data
 */
class CoupleCreator
{
    private Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Create couple records from family data
     */
    public function create(array $familyMap, array $personMap): void
    {
        foreach ($familyMap as $gedcomId => $familyData) {
            if ($familyData['husband'] && $familyData['wife'] &&
                isset($personMap[$familyData['husband']]) &&
                isset($personMap[$familyData['wife']])) {
                $person1Id = $personMap[$familyData['husband']];
                $person2Id = $personMap[$familyData['wife']];

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
}
