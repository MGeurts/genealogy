<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;

class Gallery extends Component
{
    public $person;

    public array $images = [];

    public ?int $selected = null;

    protected $listeners = [
        'photos_updated' => 'mount',
        'person_updated' => 'render',
    ];

    /**
     * Mount the component and load images.
     */
    public function mount(): void
    {
        // Fetch all the images for the person
        $path = public_path("storage/photos/{$this->person->team_id}/{$this->person->id}_*.webp");

        $this->images = collect(File::glob($path))
            ->map(fn ($p) => basename($p)) // Extract filename
            ->toArray();

        // Set selected image index, if any
        $this->selected = $this->getSelectedImageIndex();
    }

    /**
     * Get the selected image index.
     */
    protected function getSelectedImageIndex(): ?int
    {
        if (count($this->images) === 0) {
            return null;
        }

        return $this->person->photo
            ? array_search($this->person->photo, $this->images)
            : 0;
    }

    /**
     * Select the previous image in the gallery.
     */
    public function previousImage(): void
    {
        $this->selected = ($this->selected - 1 + count($this->images)) % count($this->images);
    }

    /**
     * Select the next image in the gallery.
     */
    public function nextImage(): void
    {
        $this->selected = ($this->selected + 1) % count($this->images);
    }

    /**
     * Select an image by its index.
     */
    public function selectImage(int $index): void
    {
        $this->selected = $index;
    }

    /**
     * Render the component view.
     */
    public function render(): View
    {
        return view('livewire.people.gallery');
    }
}
