<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

final class Descendants extends Component
{
    public Person $person;

    public Collection $descendants;

    public int $count_min = 1;

    public int $count = 3;          // default, showing 3 levels (person & parents & grandparents)

    public int $count_max = 128;    // maximum level depth, choose carefully from listing below

    // --------------------------------------------------------------------------------------------------------------------
    // REMARK : The maximum length of the comma separated sequence of all id's in the tree can NOT succeed 1024 characters!
    //          So, when largest id is 3 digits (max        999), the maximum level depth is 1024 / (3 + 1) = 256 levels
    //              when largest id is 4 digits (max      9.999), the maximum level depth is 1024 / (4 + 1) = 204 levels
    //              when largest id is 5 digits (max     99.999), the maximum level depth is 1024 / (5 + 1) = 170 levels
    //              when largest id is 6 digits (max    999.999), the maximum level depth is 1024 / (6 + 1) = 146 levels
    //              when largest id is 7 digits (max  9.999.999), the maximum level depth is 1024 / (7 + 1) = 128 levels
    //              when largest id is 8 digits (max 99.999.999), the maximum level depth is 1024 / (8 + 1) = 113 levels
    //              ...
    // --------------------------------------------------------------------------------------------------------------------

    /**
     * Set up the component data.
     */
    public function mount(): void
    {
        $this->loadDescendants();
    }

    /**
     * Increment the count of descendants displayed.
     */
    public function increment(): void
    {
        if ($this->count < $this->count_max) {
            $this->count++;
        }
    }

    /**
     * Decrement the count of descendants displayed.
     */
    public function decrement(): void
    {
        if ($this->count > $this->count_min) {
            $this->count--;
        }
    }

    /**
     * Render the Livewire component view.
     */
    public function render(): View
    {
        return view('livewire.people.descendants');
    }

    /**
     * Get the number of descendants actually displayed (respecting $count).
     */
    public function getDisplayedDescendantsCountProperty(): int
    {
        return $this->descendants
            ->where('degree', '<', $this->count) // keep within displayed levels
            ->count();
    }

    /**
     * Load descendants from the database with recursion.
     */
    private function loadDescendants(): void
    {
        $this->descendants = collect(DB::select($this->getRecursiveQuery()));

        $maxDegree       = $this->descendants->max('degree');
        $this->count_max = min($maxDegree + 1, $this->count_max);

        if ($this->count > $this->count_max) {
            $this->count = $this->count_max;
        }
    }

    /**
     * Build the recursive query for descendants.
     */
    private function getRecursiveQuery(): string
    {
        $personId = $this->person->id;
        $countMax = $this->count_max;

        return "
            WITH RECURSIVE descendants AS (
                SELECT
                    id, firstname, surname, sex, father_id, mother_id, dod, yod, team_id, photo, dob, yob,
                    0 AS degree,
                    CAST(id AS CHAR(1024)) AS sequence
                FROM people
                WHERE deleted_at IS NULL AND id = $personId

                UNION ALL

                SELECT
                    p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo, p.dob, p.yob,
                    d.degree + 1 AS degree,
                    CONCAT_WS(',', d.sequence, p.id) AS sequence
                FROM people p
                JOIN descendants d ON p.father_id = d.id
                WHERE p.deleted_at IS NULL AND d.degree < $countMax

                UNION ALL

                SELECT
                    p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo, p.dob, p.yob,
                    d.degree + 1 AS degree,
                    CONCAT_WS(',', d.sequence, p.id) AS sequence
                FROM people p
                JOIN descendants d ON p.mother_id = d.id
                WHERE p.deleted_at IS NULL AND d.degree < $countMax
            )
            SELECT * FROM descendants
            ORDER BY degree, dob IS NULL, dob, yob IS NULL, yob;
        ";
    }
}
