<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Descendants extends Component
{
    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------
    public Collection $descendants;

    public int $count_min = 1;

    public int $count = 3;          // default, showing 3 levels (person & parents & grandparents)

    public int $count_max = 128;    // maximum level depth, choose from listing below

    // --------------------------------------------------------------------------------------------------------------------
    // REMARK : The maximum length of the comma separated sequence of all id's in the tree can NOT succeed 1024 characters!
    //          So, when the id's are 3 digits (max        999), the maximum level depth is 1024 / (3 + 1) = 256 levels
    //              when the id's are 4 digits (max      9.999), the maximum level depth is 1024 / (4 + 1) = 204 levels
    //              when the id's are 5 digits (max     99.999), the maximum level depth is 1024 / (5 + 1) = 170 levels
    //              when the id's are 6 digits (max    999.999), the maximum level depth is 1024 / (6 + 1) = 146 levels
    //              when the id's are 7 digits (max  9.999.999), the maximum level depth is 1024 / (7 + 1) = 128 levels
    //              when the id's are 8 digits (max 99.999.999), the maximum level depth is 1024 / (8 + 1) = 113 levels
    //              ...
    // --------------------------------------------------------------------------------------------------------------------

    public function mount(): void
    {
        $this->descendants = collect(DB::select("
            WITH RECURSIVE descendants AS (
                SELECT
                    id, firstname, surname, sex, father_id, mother_id, dod, yod, team_id, photo, dob, yob,
                    0 AS degree,
                    CAST(id AS CHAR(1024)) AS sequence
                FROM people
                WHERE deleted_at IS NULL AND id = '" . $this->person->id . "'

                UNION ALL

                SELECT p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo, p.dob, p.yob, 
                    degree + 1 AS degree,
                    CAST(CONCAT(d.sequence, ',', p.id) AS CHAR(1024)) AS sequence
                FROM people p, descendants d
                WHERE deleted_at IS NULL AND (p.father_id = d.id OR p.mother_id = d.id) AND degree < '" . $this->count_max - 1 . "'
            )

            SELECT * FROM descendants ORDER BY degree, dob, yob;
        "));

        $this->count_max = $this->descendants->max('degree') <= $this->count_max ? $this->descendants->max('degree') + 1 : $this->count_max;

        if ($this->count > $this->count_max) {
            $this->count = $this->count_max;
        }
    }

    public function increment(): void
    {
        if ($this->count < $this->count_max) {
            $this->count++;
        }
    }

    public function decrement(): void
    {
        if ($this->count > $this->count_min) {
            $this->count--;
        }
    }
    
    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.descendants');
    }
}
