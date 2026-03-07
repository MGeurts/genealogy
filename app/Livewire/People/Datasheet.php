<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Contracts\PersonPhotoServiceInterface;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class Datasheet extends Component
{
    public Person $person;

    /**
     * @var Collection<int, string>
     */
    #[Locked]
    public Collection $images;

    /**
     * @var Collection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media>
     */
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
        /** @var PersonPhotoServiceInterface $photoService */
        $photoService = app(PersonPhotoServiceInterface::class);
        $filenames    = $photoService->getMediumImageFilenames($this->person);

        $this->images = collect($filenames);
    }
}
