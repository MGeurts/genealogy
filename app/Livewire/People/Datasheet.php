<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;

class Datasheet extends Component
{
    public $person;

    public array $images = [];

    public array $files = [];

    /**
     * Mount the component and load relevant data.
     */
    public function mount(): void
    {
        // Load image files for the person (webp format), returning only filenames with extensions
        $this->images = collect(File::glob(storage_path("app/public/photos/{$this->person->team_id}/{$this->person->id}_*.webp")))
            ->map(fn ($path) => basename($path)) // Extract filename with extension
            ->toArray();

        // Load the media files associated with the person and extract only filenames (including extensions)
        $this->files = $this->person->getMedia('files')
            ->map(fn ($media) => basename($media->getPath())) // Extract filename with extension from media path
            ->toArray();
    }

    /**
     * Render the Livewire view.
     */
    public function render(): View
    {
        return view('livewire.people.datasheet');
    }
}
