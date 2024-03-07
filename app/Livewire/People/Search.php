<?php

namespace App\Livewire\People;

use App\Models\Person;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    #[Session]
    public $search = '%'; // default to '' when application goes in production

    public $perpage = 10;

    public $options = [
        ['value' => 5, 'text' => 5],
        ['value' => 10, 'text' => 10],
        ['value' => 25, 'text' => 25],
        ['value' => 50, 'text' => 50],
        ['value' => 100, 'text' => 100],
    ];

    public function render()
    {
        $people_db = Person::count();

        if ($this->search) {
            $people = Person::with('father:id,firstname,surname,sex', 'mother:id,firstname,surname,sex')
                ->search($this->search)
                ->orderBy('firstname')->orderBy('surname') // reverse order when application goes in production
                ->paginate($this->perpage);
        } else {
            $people = collect([]);
        }

        return view('livewire.people.search')->with(compact('people_db', 'people'));
    }
}
