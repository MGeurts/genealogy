<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    // ------------------------------------------------------------------------------
    #[Session]
    public $search = null;

    public int $perpage = 10;

    public array $options = [
        ['value' => 5, 'label' => 5],
        ['value' => 10, 'label' => 10],
        ['value' => 25, 'label' => 25],
        ['value' => 50, 'label' => 50],
        ['value' => 100, 'label' => 100],
    ];

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        //
    }

    // -----------------------------------------------------------------------
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerpage(): void
    {
        $this->resetPage();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        $people_db = Person::count();

        $people = Person::with('father:id,firstname,surname,sex,yod,dod', 'mother:id,firstname,surname,sex,yod,dod')
            ->search($this->search ? $this->search : '%')
            ->orderBy('firstname')->orderBy('surname')
            ->paginate($this->perpage);

        return view('livewire.people.search', compact('people_db', 'people'));
    }
}
