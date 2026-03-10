<?php

declare(strict_types=1);

use App\Models\Person;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    /**
     * @var Collection<int, Spatie\MediaLibrary\MediaCollections\Models\Media>
     */
    public Collection $files;

    // ------------------------------------------------------------------------------
    #[On('files_updated')]
    public function mount(): void
    {
        $this->files = $this->person->getMedia('files');
    }
};
