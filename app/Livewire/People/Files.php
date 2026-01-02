<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

final class Files extends Component
{
    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    /**
     * @var Collection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media>
     */
    public Collection $files;

    // ------------------------------------------------------------------------------
    /**
     * @var array<string, string>
     */
    protected $listeners = [
        'files_updated' => 'mount',
    ];

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->files = $this->person->getMedia('files');
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.files');
    }
}
