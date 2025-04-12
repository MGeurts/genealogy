<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

final class Siblings extends Component
{
    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------
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
