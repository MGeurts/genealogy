<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\View\View;
use Livewire\Component;

final class Heading extends Component
{
    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.heading');
    }
}
