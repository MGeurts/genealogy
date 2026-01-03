<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use App\Models\Couple;
use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;
use stdClass;

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
     * @param  Collection<int, Person>  $individuals  Collection of Person models
     * @param  Collection<int, Couple>  $couples  Collection of couple models
     * @return SupportCollection<int, object> GEDCOM family structures
     */
    public function buildGedcomFamilies(Collection $individuals, Collection $couples): SupportCollection
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

                $family = $this->createCoupleFamily(
                    $couple,
                    $couples->where('person1_id', $couple->person1_id)
                        ->where('person2_id', $couple->person2_id)
                        ->merge($couples->where('person1_id', $couple->person2_id)
                            ->where('person2_id', $couple->person1_id))
                );

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

                    $parentFamily = $this->createParentFamily($familyId, $person->father_id, $person->mother_id);

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
                if ($family !== null) {
                    /** @var SupportCollection<int, Person> $children */
                    $children = $family->children;
                    $children->push($person);
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
     * @param  SupportCollection<int, object>  $gedcomFamilies  GEDCOM family structures
     * @return array<int, array<int>> Person ID to family IDs mapping
     */
    public function buildFamilyMapping(SupportCollection $gedcomFamilies): array
    {
        $famsMapping = [];

        foreach ($gedcomFamilies as $family) {
            // Every family where a person is person1 or person2 (i.e., an adult/parent)
            // should result in a FAMS tag for that person

            if (property_exists($family, 'person1_id') && $family->person1_id) {
                if (! isset($famsMapping[$family->person1_id])) {
                    $famsMapping[$family->person1_id] = [];
                }
                if (property_exists($family, 'id')) {
                    $famsMapping[$family->person1_id][] = $family->id;
                    Log::info("Person {$family->person1_id} gets FAMS for family {$family->id}");
                }
            }

            if (property_exists($family, 'person2_id') && $family->person2_id) {
                if (! isset($famsMapping[$family->person2_id])) {
                    $famsMapping[$family->person2_id] = [];
                }
                if (property_exists($family, 'id')) {
                    $famsMapping[$family->person2_id][] = $family->id;
                    Log::info("Person {$family->person2_id} gets FAMS for family {$family->id}");
                }
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
     * @param  SupportCollection<int, object>  $gedcomFamilies  GEDCOM family structures
     * @return string All family records
     */
    public function buildFamilies(SupportCollection $gedcomFamilies): string
    {
        $gedcom = '';

        foreach ($gedcomFamilies as $family) {
            $gedcom .= $this->buildFamilyRecord($family);
        }

        return $gedcom;
    }

    /**
     * Create a couple family structure.
     *
     * @param  Collection<int, Couple>  $relationships
     */
    private function createCoupleFamily(Couple $couple, Collection $relationships): object
    {
        $family                = new stdClass();
        $family->id            = $couple->id;
        $family->type          = 'couple';
        $family->person1_id    = $couple->person1_id;
        $family->person2_id    = $couple->person2_id;
        $family->relationships = $relationships;
        $family->children      = collect();

        return $family;
    }

    /**
     * Create a parent-only family structure.
     */
    private function createParentFamily(int $id, ?int $person1_id, ?int $person2_id): object
    {
        $family             = new stdClass();
        $family->id         = $id;
        $family->type       = 'parent';
        $family->person1_id = $person1_id;
        $family->person2_id = $person2_id;
        $family->children   = collect();

        return $family;
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
     * @param  object  $family  GEDCOM family object
     * @return string Family GEDCOM record
     */
    private function buildFamilyRecord(object $family): string
    {
        if (! property_exists($family, 'id')) {
            return '';
        }

        $fid   = "@F{$family->id}@";
        $lines = ["0 {$fid} FAM"];

        // Parents/Spouses
        if (property_exists($family, 'person1_id') && $family->person1_id) {
            $lines[] = "1 HUSB @I{$family->person1_id}@";
        }
        if (property_exists($family, 'person2_id') && $family->person2_id) {
            $lines[] = "1 WIFE @I{$family->person2_id}@";
        }

        // Marriage/relationship information (only for couple type families)
        if (property_exists($family, 'type') && $family->type === 'couple') {
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
     * @param  object  $family  Family object with relationship data
     * @return array<string> Marriage field lines
     */
    private function buildFamilyFields(object $family): array
    {
        $lines = [];

        // Handle multiple relationship periods if they exist
        if (property_exists($family, 'relationships') && $family->relationships instanceof Collection) {
            foreach ($family->relationships as $relationship) {
                // Marriage event if marked as married
                if (property_exists($relationship, 'is_married') && $relationship->is_married) {
                    $lines[] = '1 MARR';

                    if (property_exists($relationship, 'date_start') && $relationship->date_start) {
                        if ($d = $this->formatter->formatGedcomDate($relationship->date_start)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }

                    // If relationship has ended and they were married, add divorce
                    if (property_exists($relationship, 'has_ended') &&
                        property_exists($relationship, 'date_end') &&
                        $relationship->has_ended &&
                        $relationship->date_end) {
                        $lines[] = '1 DIV';
                        if ($d = $this->formatter->formatGedcomDate($relationship->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
                } else {
                    // Non-married relationship
                    $lines[] = '1 EVEN';
                    $lines[] = '2 TYPE Relationship';

                    if (property_exists($relationship, 'date_start') && $relationship->date_start) {
                        if ($d = $this->formatter->formatGedcomDate($relationship->date_start)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }

                    // If relationship has ended
                    if (property_exists($relationship, 'has_ended') &&
                        property_exists($relationship, 'date_end') &&
                        $relationship->has_ended &&
                        $relationship->date_end) {
                        $lines[] = '1 EVEN';
                        $lines[] = '2 TYPE End of relationship';
                        if ($d = $this->formatter->formatGedcomDate($relationship->date_end)) {
                            $lines[] = "2 DATE {$d}";
                        }
                    }
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
     * @param  object  $family  Family object with children collection
     * @return array<string> Children field lines
     */
    private function buildChildrenFields(object $family): array
    {
        $lines = [];

        // Children are already collected in the family object
        if (property_exists($family, 'children') && $family->children instanceof SupportCollection) {
            foreach ($family->children as $child) {
                $lines[] = "1 CHIL @I{$child->id}@";
            }
        }

        return $lines;
    }
}
