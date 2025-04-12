<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\View\View;
use Livewire\Component;

final class Person extends Component
{
    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.person');
    }
}
