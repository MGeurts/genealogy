<?php

declare(strict_types=1);

use App\Models\Person;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    #[On('couple_added')]
    #[On('couple_deleted')]
    #[On('family_updated')]
    #[On('father_added')]
    #[On('mother_added')]
    public function refreshFamily(): void
    {
        // optionally refresh any data here
        // Livewire will re-render automatically
    }
};
