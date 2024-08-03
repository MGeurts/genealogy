<?php

namespace App\Livewire\People;

use Illuminate\Support\Collection;
use Livewire\Component;

class Files extends Component
{
    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------
    public Collection $files;
    
    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->files = $this->person->getMedia('files');
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.files');
    }
}
