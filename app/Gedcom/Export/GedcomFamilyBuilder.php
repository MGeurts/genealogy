<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use App\Models\Couple;
use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

// ==============================================================================
// GEDCOM FAMILY BUILDER - Handles family records and relationships
// ==============================================================================

/**
 * GEDCOM Family Builder Class
 *
 * Manages the complex process of building family structures and relationships,
 * including:
 * - Converting couple relationships to GEDCOM families
 * - Creating parent-child family structures
 * - Handling marriage and relationship events
 * - Managing family mapping for FAMC/FAMS references
 */
class GedcomFamilyBuilder
{
    /** @var array<string, int> Mapping of parent combinations to family IDs */
    private array $parentFamilyMapping = [];

    /**
     * Create family builder instance.
     *
     * @param  GedcomFormatter  $formatter  Text formatter instance
     */
    public function __construct(private GedcomFormatter $formatter) {}

    // --------------------------------------------------------------------------------------
    // FAMILY STRUCTURE BUILDING
    // --------------------------------------------------------------------------------------

    /**
     * Build GEDCOM family structures from individuals and couples.
     *
     * Creates a comprehensive family structure that includes both couple
     * relationships and parent-child relationships, ensuring proper
     * GEDCOM family record organization.
     *
     * @param  Collection<Person>  $individuals  Collection of Person models
     * @param  Collection<int, Couple>  $couples  Collection of couple models
     * @return \Illuminate\Support\Collection GEDCOM family structures
     */
    public function buildGedcomFamilies(Collection $individuals, Collection $couples): \Illuminate\Support\Collection
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
     * Build family mapping for FAMS references.
     *
     * Creates mapping from person IDs to family IDs where they appear
     * as spouses/partners, ensuring every adult gets proper FAMS tags.
     *
     * @param  \Illuminate\Support\Collection  $gedcomFamilies  GEDCOM family structures
     * @return array<int, array<int>> Person ID to family IDs mapping
     */
    public function buildFamilyMapping(\Illuminate\Support\Collection $gedcomFamilies): array
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

    /**
     * Get the parent family ID for a person based on their parent relationships.
     *
     * Determines which family a person belongs to as a child, considering
     * both individual parent fields and couple-based parent references.
     *
     * @param  Person  $person  Person model instance
     * @return int|null Parent family ID if found
     */
    public function getPersonParentFamilyId(Person $person): ?int
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

    // --------------------------------------------------------------------------------------
    // FAMILY RECORD BUILDING
    // --------------------------------------------------------------------------------------

    /**
     * Build all family records.
     *
     * Processes the collection of GEDCOM family structures and generates
     * complete family records with relationships and children.
     *
     * @param  \Illuminate\Support\Collection  $gedcomFamilies  GEDCOM family structures
     * @return string All family records
     */
    public function buildFamilies(\Illuminate\Support\Collection $gedcomFamilies): string
    {
        $gedcom = '';

        foreach ($gedcomFamilies as $family) {
            $gedcom .= $this->buildFamilyRecord($family);
        }

        return $gedcom;
    }

    /**
     * Generate a unique key for a parent combination.
     *
     * Creates a consistent key for parent pairs regardless of order,
     * allowing proper family matching across different data sources.
     *
     * @param  int|null  $parent1Id  First parent ID
     * @param  int|null  $parent2Id  Second parent ID
     * @return string Unique parent combination key
     */
    private function getParentKey(?int $parent1Id, ?int $parent2Id): string
    {
        return collect([$parent1Id, $parent2Id])
            ->filter()
            ->sort()
            ->implode('-');
    }

    /**
     * Build a single family record.
     *
     * Creates a complete GEDCOM family record including spouse references,
     * marriage/relationship events, and child references.
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

        return implode($this->formatter->eol(), $lines) . $this->formatter->eol();
    }

    /**
     * Build marriage-related fields for a family.
     *
     * Handles various relationship types including marriages, partnerships,
     * and their associated events (start, end, divorce) with proper dating.
     *
     * @param  mixed  $family  Family object with relationship data
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
                        if ($d = $this->formatter->formatGedcomDate($relationship->date_start)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }

                    // If relationship has ended and they were married, add divorce
                    if ($relationship->has_ended && $relationship->date_end) {
                        $lines[] = '1 DIV';
                        if ($d = $this->formatter->formatGedcomDate($relationship->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
                } else {
                    // Non-married relationship
                    $lines[] = '1 EVEN';
                    $lines[] = '2 TYPE Relationship';

                    if ($relationship->date_start) {
                        if ($d = $this->formatter->formatGedcomDate($relationship->date_start)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }

                    // If relationship has ended
                    if ($relationship->has_ended && $relationship->date_end) {
                        $lines[] = '1 EVEN';
                        $lines[] = '2 TYPE End of relationship';
                        if ($d = $this->formatter->formatGedcomDate($relationship->date_end)) {
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
                    if ($d = $this->formatter->formatGedcomDate($family->date_start)) {
                        $lines[] = "2 DATE {$d}";
                    }
                }
            }

            if ($family->has_ended) {
                if ($family->is_married) {
                    $lines[] = '1 DIV';
                    if ($family->date_end) {
                        if ($d = $this->formatter->formatGedcomDate($family->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
                } else {
                    $lines[] = '1 EVEN';
                    $lines[] = '2 TYPE End of relationship';
                    if ($family->date_end) {
                        if ($d = $this->formatter->formatGedcomDate($family->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
                }
            }

            if (! $family->is_married && $family->date_start) {
                $lines[] = '1 EVEN';
                $lines[] = '2 TYPE Beginning of relationship';
                if ($d = $this->formatter->formatGedcomDate($family->date_start)) {
                    $lines[] = "2 DATE {$d}";
                }
            }
        }

        return $lines;
    }

    /**
     * Build children fields for a family.
     *
     * Creates CHIL references for all children associated with this family.
     *
     * @param  mixed  $family  Family object with children collection
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
}
