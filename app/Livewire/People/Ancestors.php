<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Ancestors extends Component
{
    public $person;

    public $ancestors;

    public $count = 3;          // default

    public $count_min = 1;

    public $count_max = 128;    // maximum level depth

    // --------------------------------------------------------------------------------------------------------------------
    // REMARK : The maximum length of the comma separated sequence of all id's in the tree can NOT succeed 1024 characters!
    //          So, when the id's are 3 digits, the maximum level depth is 1024 / (3 + 1) = 256 levels
    //          So, when the id's are 4 digits, the maximum level depth is 1024 / (4 + 1) = 204 levels
    //          So, when the id's are 5 digits, the maximum level depth is 1024 / (5 + 1) = 170 levels
    //          So, when the id's are 6 digits, the maximum level depth is 1024 / (6 + 1) = 146 levels
    //          So, when the id's are 7 digits, the maximum level depth is 1024 / (6 + 1) = 128 levels
    //          ...
    // --------------------------------------------------------------------------------------------------------------------
    public function increment()
    {
        if ($this->count < $this->count_max) {
            $this->count++;
        }
    }

    public function decrement()
    {
        if ($this->count > $this->count_min) {
            $this->count--;
        }
    }

    public function mount()
    {
        $this->ancestors = collect(DB::select("
            WITH RECURSIVE ancestors AS ( 
	            SELECT 
                    id, firstname, surname, sex, father_id, mother_id, dod, yod, team_id, photo, 
		            0 AS degree,
                    CAST(CONCAT(id, '') AS CHAR(1024)) AS sequence
	            FROM people  
	            WHERE deleted_at IS NULL AND id = " . $this->person->id . " 
    
	            UNION ALL 
    
	            SELECT p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo,
		            degree + 1 AS degree,
                    CONCAT(a.sequence, ',', p.id) AS sequence
	            FROM people p, ancestors a 
	            WHERE deleted_at IS NULL AND (p.id = a.father_id OR p.id = a.mother_id)
            ) 
        
            SELECT * FROM ancestors ORDER BY degree, sex DESC;
        "));

        $this->count_max = $this->ancestors->max('degree') <= $this->count_max ? $this->ancestors->max('degree') + 1 : $this->count_max;

        if ($this->count > $this->count_max) {
            $this->count = $this->count_max;
        }
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.ancestors');
    }
}
