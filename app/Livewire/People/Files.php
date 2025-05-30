<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Collection;
use Livewire\Component;

final class Files extends Component
{
    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    public Collection $files;

    // ------------------------------------------------------------------------------
    protected $listeners = [
        'files_updated' => 'mount',
    ];

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
