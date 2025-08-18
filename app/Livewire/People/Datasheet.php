<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class Datasheet extends Component
{
    public Person $person;

    #[Locked]
    public Collection $images;

    #[Locked]
    public Collection $files;

    /**
     * Mount the component and load relevant data.
     */
    public function mount(): void
    {
        $this->loadImages();

        $this->files = $this->person->getMedia('files');
    }

    /**
     * Render the Livewire view.
     */
    public function render(): View
    {
        return view('livewire.people.datasheet');
    }

    protected function loadImages(): void
    {
        $disk       = Storage::disk('photos');
        $personPath = "{$this->person->team_id}/{$this->person->id}";

        if (! $disk->exists($personPath)) {
            $this->images = collect();

            return;
        }

        // List all files in the person's folder
        $allFiles = collect($disk->files($personPath));

        // Filter only medium images that belong to this person
        $this->images = $allFiles
            ->filter(fn ($file) => str_starts_with(basename($file), "{$this->person->id}_") && str_ends_with($file, '_medium.webp'))
            ->map(fn ($file) => basename($file));
    }
}
