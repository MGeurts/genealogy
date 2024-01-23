<?php

namespace App\Livewire\People;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Descendants extends Component
{
    public $person;

    public $descendants;

    public $count = 3;              // default

    public $count_min = 1;

    public $count_max = 10;

    protected $listeners = [
        'photo_updated' => 'mount',
    ];

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
        $this->descendants = collect(DB::select("
            WITH RECURSIVE descendants AS ( 
                SELECT 
                    id, firstname, surname, sex, father_id, mother_id, dob, dod, yod, photo,
                    0 AS degree,
                    CONCAT(id, '') AS sequence
                FROM people  
                WHERE deleted_at IS NULL AND id = " . $this->person->id . " 
                
                UNION ALL 
                
                SELECT p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dob, p.dod, p.yod, p.photo,
                    degree + 1 AS degree,
                    CONCAT(d.sequence, ',', p.id) AS sequence
                FROM people p, descendants d
                WHERE deleted_at IS NULL AND (p.father_id = d.id OR p.mother_id = d.id)
            ) 
                    
            SELECT * FROM descendants ORDER BY degree, dob;
        "));

        $this->count_max = $this->descendants->max('degree') + 1;

        if ($this->count > $this->count_max) {
            $this->count = $this->count_max;
        }
    }

    public function render()
    {
        return view('livewire.people.descendants');
    }
}
