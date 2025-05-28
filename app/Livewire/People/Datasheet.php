<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
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
        $directory = storage_path("app/public/photos/{$this->person->team_id}");

        if (! File::exists($directory)) {
            $this->images = collect();

            return;
        }

        $pattern = "{$directory}/{$this->person->id}_*.webp";

        // Load image files for the person (webp format), returning only filenames with extensions
        $this->images = collect(File::glob($pattern))
            ->map(fn ($path) => basename($path)); // Extract filename with extension
    }
}
