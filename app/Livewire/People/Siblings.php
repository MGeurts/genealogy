<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

final class Siblings extends Component
{
    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    /** @var Collection<int, Person> */
    public Collection $siblings;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->siblings = $this->person->siblings();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.siblings');
    }
}
