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

    /** @var array<int, array{filename: string, small: string|null, medium: string|null, large: string|null, original: string}> */
    public array $images = [];

    public ?int $selected = null;

    /** @var array<string, string> */
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

        // Get all original files (files without _large, _medium, _small suffix)
        $this->images = collect($allFiles)
            ->filter(function ($file) {
                $basename = basename($file);

                return ! str_contains($basename, '_large.')
                    && ! str_contains($basename, '_medium.')
                    && ! str_contains($basename, '_small.');
            })
            ->map(function ($originalFile) use ($directory) {
                $baseName = basename($originalFile);

                // Extract filename without extension for database comparison
                $filenameWithoutExt = pathinfo($baseName, PATHINFO_FILENAME);

                // Build paths for variants
                $largeFile  = $directory . '/' . $filenameWithoutExt . '_large.webp';
                $mediumFile = $directory . '/' . $filenameWithoutExt . '_medium.webp';
                $smallFile  = $directory . '/' . $filenameWithoutExt . '_small.webp';

                return [
                    'filename' => $filenameWithoutExt, // Store filename without extension for database comparison
                    'small'    => Storage::disk('photos')->exists($smallFile) ? Storage::disk('photos')->url($smallFile) : null,
                    'medium'   => Storage::disk('photos')->exists($mediumFile) ? Storage::disk('photos')->url($mediumFile) : null,
                    'large'    => Storage::disk('photos')->exists($largeFile) ? Storage::disk('photos')->url($largeFile) : null,
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
