<?php

declare(strict_types=1);

use App\Models\Person;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    /** @var Collection<int, Person> */
    public Collection $siblings;

    // ------------------------------------------------------------------------------
    #[On('family_updated')]
    #[On('father_added')]
    #[On('mother_added')]
    public function mount(): void
    {
        $this->siblings = $this->person->siblings();
    }
};
