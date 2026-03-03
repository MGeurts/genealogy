<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Contracts\PersonPhotoServiceInterface;
use App\Models\Person;
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
        /** @var PersonPhotoServiceInterface $photoService */
        $photoService = app(PersonPhotoServiceInterface::class);
        $this->images = $photoService->getGalleryImages($this->person);

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

        // Find the image whose id matches the one stored in the database
        $index = collect($this->images)->search(function ($image) {
            return $image['id'] === $this->person->photo;
        });

        // Return the found index, or 0 if not found
        return $index !== false ? $index : 0;
    }
}
