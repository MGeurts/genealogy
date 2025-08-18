<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;

final class Gallery extends Component
{
    public Person $person;

    public array $images = [];

    public ?int $selected = null;

    protected $listeners = [
        'photos_updated' => 'mount',
        'person_updated' => 'render',
    ];

    public function mount(): void
    {
        // Get files from the person's directory
        $directory = "{$this->person->team_id}/{$this->person->id}";

        if (! Storage::disk('photos')->exists($directory)) {
            $this->images   = [];
            $this->selected = null;

            return;
        }

        $allFiles = Storage::disk('photos')->files($directory);

        // Get all medium files and match them with originals
        $this->images = collect($allFiles)
            ->filter(fn ($file) => str_ends_with($file, '_medium.webp'))
            ->map(function ($mediumFile) {
                // Extract the base filename (without _medium.webp)
                $baseName     = basename($mediumFile);
                $originalName = str_replace('_medium.webp', '.webp', $baseName);
                $originalFile = str_replace($baseName, $originalName, $mediumFile);

                // Extract filename without extension for database comparison
                $filenameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

                return [
                    'filename' => $filenameWithoutExt, // Store filename without extension for database comparison
                    'medium'   => Storage::disk('photos')->url($mediumFile),
                    'original' => Storage::disk('photos')->url($originalFile),
                ];
            })
            ->sortBy('filename')
            ->values()
            ->toArray();

        $this->selected = $this->getPrimaryImageIndex();
    }

    public function previousImage(): void
    {
        if (count($this->images) === 0) {
            return;
        }

        $this->selected = ($this->selected - 1 + count($this->images)) % count($this->images);
    }

    public function nextImage(): void
    {
        if (count($this->images) === 0) {
            return;
        }

        $this->selected = ($this->selected + 1) % count($this->images);
    }

    public function selectImage(?int $index): void
    {
        $this->selected = $index;
    }

    public function render(): View
    {
        return view('livewire.people.gallery');
    }

    /**
     * Get the index of the primary image by matching the filename stored in the database.
     * The database stores the filename without extension (e.g., "{personId}_{index}_{timestamp}"),
     * and we need to find its index in the images array.
     */
    protected function getPrimaryImageIndex(): ?int
    {
        if (empty($this->images)) {
            return null;
        }

        if (! $this->person->photo) {
            return 0;
        }

        // Find the image whose filename matches the one stored in the database
        $index = collect($this->images)->search(function ($image) {
            return $image['filename'] === $this->person->photo;
        });

        // Return the found index, or 0 if not found
        return $index !== false ? $index : 0;
    }
}
