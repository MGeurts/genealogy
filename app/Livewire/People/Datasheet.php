<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\View\View;
use Livewire\Component;

class Datasheet extends Component
{
    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------

    // ------------------------------------------------------------------------------
    public function mount(): void {}

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.datasheet');
    }
}
