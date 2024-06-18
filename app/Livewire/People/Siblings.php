<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Livewire\Component;

class Siblings extends Component
{
    public $person;

    // ------------------------------------------------------------------------------
    public function render()
    {
        $siblings = $this->person->siblings();

        return view('livewire.people.siblings', compact('siblings'));
    }
}
