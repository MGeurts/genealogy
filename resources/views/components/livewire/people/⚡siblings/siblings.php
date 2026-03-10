<?php

declare(strict_types=1);

use App\Models\Person;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component
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
};
