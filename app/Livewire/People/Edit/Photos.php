<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Models\Person;
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

final class Photos extends Component
{
    use Interactions;
    use WithFileUploads;

    public Person $person;

    public array $uploads = [];

    public array $backup = [];

    public ?Collection $photos = null;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadPhotos();
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
            ->filter(fn (UploadedFile $file): bool => $file->getFilename() !== $content['temporary_name'])
            ->values()
            ->toArray();

        rescue(
            fn () => File::delete(storage_path('app/livewire-tmp/' . $content['temporary_name'])),
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
        $this->validate();

        if (empty($this->uploads)) {
            return;
        }

        $this->uploads = collect(array_merge($this->backup, (array) $this->uploads))
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->toArray();
    }

    /**
     * Save uploaded photos.
     */
    public function save(): void
    {
        $this->validate();

        if ($this->uploads) {
            $personPhotos = new PersonPhotos($this->person);

            $personPhotos->save($this->uploads);

            $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', count($this->uploads)))->send();

            $this->dispatch('photos_updated');

            // Reload the photos collection to reflect changes in the UI
            $this->loadPhotos();

            // Clear uploads array
            $this->uploads = [];
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

        $this->toast()->success(__('app.delete'), __('person.photo_deleted'))->send();

        $this->dispatch('photos_updated');

        // Reload the photos collection to reflect changes in the UI
        $this->loadPhotos();
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
    protected function rules(): array
    {
        return [
            'uploads.*' => [
                'file',
                'mimetypes:' . implode(',', array_keys(config('app.upload_photo_accept'))),
                'max:' . config('app.upload_max_size'),
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'uploads.*.file'      => __('validation.file', ['attribute' => __('person.photo')]),
            'uploads.*.mimetypes' => __('validation.mimetypes', [
                'attribute' => __('person.photo'),
                'values'    => implode(', ', array_values(config('app.upload_photo_accept'))),
            ]),
            'uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photo'),
                'max'       => config('app.upload_max_size'),
            ]),
        ];
    }

    // -----------------------------------------------------------------------
    private function loadPhotos(): void
    {
        $this->photos = collect($this->getPersonPhotos())
            ->map(fn (SplFileInfo $file): array => $this->mapPhotoData($file))
            ->sortBy('name');
    }

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
            'size'          => Number::fileSize($file->getSize(), 2),
            'path'          => $file->getPath(),
            'url'           => Storage::url("photos-384/{$teamId}/{$file->getFilename()}"),
            'url_original'  => Storage::url("photos/{$teamId}/{$file->getFilename()}"),
        ];
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

        $newPrimary = $files ? basename((string) $files[0]) : null;

        $this->person->update(['photo' => $newPrimary]);
    }
}
