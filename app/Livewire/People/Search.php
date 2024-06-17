<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    // ------------------------------------------------------------------------------
    #[Session]
    public $search = '%'; // default to '' when application goes in production

    public $perpage = 10;

    public $options = [
        ['value' => 5, 'label' => 5],
        ['value' => 10, 'label' => 10],
        ['value' => 25, 'label' => 25],
        ['value' => 50, 'label' => 50],
        ['value' => 100, 'label' => 100],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    
    public function updatedPerpage(): void
    {
        $this->resetPage();
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        $people_db = Person::count();

        if ($this->search) {
            $people = Person::with('father:id,firstname,surname,sex,yod,dod', 'mother:id,firstname,surname,sex,yod,dod')
                ->search($this->search)
                ->orderBy('firstname')->orderBy('surname') // reverse order when application goes in production
                ->paginate($this->perpage);
        } else {
            $people = collect([]);
        }

        return view('livewire.people.search')->with(compact('people_db', 'people'));
    }
}
