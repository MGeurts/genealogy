<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Livewire\Component;

class Person extends Component
{
    public $person;

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.person');
    }
}
