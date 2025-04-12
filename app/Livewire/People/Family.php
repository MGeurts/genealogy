<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\View\View;
use Livewire\Component;

final class Family extends Component
{
    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------
    protected $listeners = [
        'couple_deleted' => 'render',
    ];

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.family');
    }
}
