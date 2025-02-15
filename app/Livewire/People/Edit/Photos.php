<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\PersonPhotos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use TallStackUi\Traits\Interactions;

class Photos extends Component
{
    use Interactions;
    use WithFileUploads;

    public $person;

    public array $uploads = [];

    public array $backup = [];

    public ?Collection $photos = null;

    /**
     * Initialize component and prepare photo directories.
     */
    public function mount(): void
    {
        // Load existing photos for the person.
        $this->photos = collect($this->getPersonPhotos())
            ->map(fn (SplFileInfo $file) => $this->mapPhotoData($file))
            ->sortBy('name');
    }

    /**
     * Handle file deletion from uploads.
     */
    public function deleteUpload(array $content): void
    {
        /* the $content contains:
            [
                'temporary_name',
                'real_name',
                'extension',
                'size',
                'path',
                'url',
            ]
        */

        if (empty($this->uploads)) {
            return;
        }

        $this->uploads = collect($this->uploads)
            ->filter(fn (UploadedFile $file) => $file->getFilename() !== $content['temporary_name'])
            ->values()
            ->toArray();

        rescue(
            fn () => UploadedFile::deleteTemporaryFile($content['temporary_name']),
            report: false
        );
    }

    /**
     * Handle updates to the uploads property.
     */
    public function updatingUploads(): void
    {
        $this->backup = $this->uploads;
    }

    /**
     * Process uploaded files and remove duplicates.
     */
    public function updatedUploads(): void
    {
        if (empty($this->uploads)) {
            return;
        }

        $this->uploads = collect(array_merge($this->backup, (array) $this->uploads))
            ->unique(fn (UploadedFile $file) => $file->getClientOriginalName())
            ->toArray();
    }

    /**
     * Save uploaded photos.
     */
    public function save(): void
    {
        if ($this->uploads) {
            $personPhotos = new PersonPhotos($this->person);

            $personPhotos->save($this->uploads);

            $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', count($this->uploads)))->flash()->send();

            $this->redirect(route('people.edit-photos', ['person' => $this->person->id]));
        }
    }

    /**
     * Delete a photo and update primary photo if necessary.
     */
    public function deletePhoto(string $photo): void
    {
        $this->deletePhotoFiles($photo, $this->person->team_id);

        if ($photo === $this->person->photo) {
            $this->setNewPrimaryPhoto();
        }

        $this->toast()->success(__('app.delete'), __('person.photo_deleted'))->flash()->send();

        $this->dispatch('photos_updated');

        $this->mount();
    }

    /**
     * Set the primary photo for the person.
     */
    public function setPrimary(string $photo): void
    {
        $this->person->update(['photo' => $photo]);

        $this->dispatch('photos_updated');
    }

    /**
     * Render the component view.
     */
    public function render(): View
    {
        return view('livewire.people.edit.photos');
    }

    // -----------------------------------------------------------------------

    /**
     * Retrieve person photos.
     */
    private function getPersonPhotos(): Finder
    {
        $teamId = $this->person->team_id;

        return Finder::create()
            ->in(public_path("storage/photos/{$teamId}"))
            ->name("{$this->person->id}_*.webp");
    }

    /**
     * Map photo data for easier access.
     */
    private function mapPhotoData(SplFileInfo $file): array
    {
        $teamId = $this->person->team_id;

        return [
            'name'          => $file->getFilename(),
            'name_download' => "{$this->person->name} - {$file->getFilename()}",
            'extension'     => $file->getExtension(),
            'size'          => Number::fileSize($file->getSize(), 1),
            'path'          => $file->getPath(),
            'url'           => Storage::url("photos-384/{$teamId}/{$file->getFilename()}"),
            'url_original'  => Storage::url("photos/{$teamId}/{$file->getFilename()}"),
        ];
    }

    /**
     * Filter uploads by temporary name.
     */
    private function filterUploadsByTemporaryName(string $temporaryName): array
    {
        return collect($this->uploads)
            ->filter(fn (UploadedFile $item) => $item->getFilename() !== $temporaryName)
            ->toArray();
    }

    /**
     * Delete photo files from all storage locations.
     */
    private function deletePhotoFiles(string $photo, int $teamId): void
    {
        foreach (config('app.photo_folders') as $folder) {
            Storage::disk($folder)->delete("{$teamId}/{$photo}");
        }
    }

    /**
     * Set a new primary photo if the current one is deleted.
     */
    private function setNewPrimaryPhoto(): void
    {
        $files = File::glob(public_path("storage/photos/{$this->person->team_id}/{$this->person->id}_*.webp"));

        $newPrimary = $files ? basename($files[0]) : null;

        $this->person->update(['photo' => $newPrimary]);
    }
}
