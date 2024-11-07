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
    public ?string $search = null;

    public int $perpage = 10;

    // List of pagination options
    public array $options = [
        ['value' => 5, 'label' => 5],
        ['value' => 10, 'label' => 10],
        ['value' => 25, 'label' => 25],
        ['value' => 50, 'label' => 50],
        ['value' => 100, 'label' => 100],
    ];

    public int $people_db = 0;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        // Count the number of people in the database
        $this->people_db = Person::count();
    }

    // ------------------------------------------------------------------------------
    public function updatedSearch(): void
    {
        // Reset page on search change
        $this->resetPage();
    }

    public function updatedPerpage(): void
    {
        // Reset page when perpage changes
        $this->resetPage();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        // Begin query builder
        $query = Person::query();

        // Only add search condition if $search is not empty
        if ($this->search) {
            $query->search($this->search);
        }

        $query->with('father:id,firstname,surname,sex,yod,dod', 'mother:id,firstname,surname,sex,yod,dod')
            ->orderBy('firstname')
            ->orderBy('surname');

        // Paginate the results with the given perpage value
        $people = $query->paginate($this->perpage);

        return view('livewire.people.search', compact('people'));
    }
}
