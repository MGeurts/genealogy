<?php

namespace App\Livewire\People;

use Livewire\Component;

class Heading extends Component
{
    public $person;

    // -----------------------------------------------------------------------
    protected $listeners = [
        'files_updated' => 'render',
    ];

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.heading');
    }
}
