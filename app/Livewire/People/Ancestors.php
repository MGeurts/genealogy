<?php

namespace App\Livewire\People;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Ancestors extends Component
{
    public $person;

    public $ancestors;

    public $count = 3;              // default

    public $count_min = 1;

    public $count_max = 10;

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
                    id, firstname, surname, sex, father_id, mother_id, dod, yod, photo, 
		            0 AS degree,
                    CONCAT(id, '') AS sequence
	            FROM people  
	            WHERE deleted_at IS NULL AND id = " . $this->person->id . " 
    
	            UNION ALL 
    
	            SELECT p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.photo,
		            degree + 1 AS degree,
                    CONCAT(a.sequence, ',', p.id) AS sequence
	            FROM people p, ancestors a 
	            WHERE deleted_at IS NULL AND (p.id = a.father_id OR p.id = a.mother_id)
            ) 
        
            SELECT * FROM ancestors ORDER BY degree, sex DESC;
        "));

        $this->count_max = $this->ancestors->max('degree') + 1;

        if ($this->count > $this->count_max) {
            $this->count = $this->count_max;
        }
    }

    public function render()
    {
        return view('livewire.people.ancestors');
    }
}
