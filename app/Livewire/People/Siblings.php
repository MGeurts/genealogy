<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Livewire\Component;

class Siblings extends Component
{
    public $person;

    public $siblings = [];

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->siblings = $this->person->siblings();
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.siblings');
    }
}
