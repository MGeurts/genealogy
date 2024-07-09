<?php

declare(strict_types=1);

namespace App\Livewire\Gedcom;

use Livewire\Component;

class Export extends Component
{
    // -----------------------------------------------------------------------
    public function mount(): void
    {
        //
    }

    // -----------------------------------------------------------------------
    public function render()
    {
        return view('livewire.gedcom.export');
    }
}
