<?php

declare(strict_types=1);

use App\Models\Person;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component
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
    
};
