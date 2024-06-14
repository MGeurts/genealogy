<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Livewire\Component;

class Family extends Component
{
    public $person;

    // -----------------------------------------------------------------------
    protected $listeners = [
        'couple_deleted' => 'render',
    ];

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.family');
    }
}
