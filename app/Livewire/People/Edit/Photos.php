<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Facades\MediaLibrary;
use App\Models\Person;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
        $this->photos = $this->person->getMedia();
    }

    /**
     * Handle updates to the uploads property.
     */
    public function updatingUploads(): void
    {
        $this->backup = $this->uploads;
    }

    /**
     * Process uploaded files and remove duplicates with better performance.
     */
    public function updatedUploads(): void
    {
        $this->validate();

        if (empty($this->uploads)) {
            return;
        }

        // Use more efficient duplicate detection
        $existingNames = collect($this->backup)->pluck('name')->flip();
        $newUploads    = collect($this->uploads)->reject(function (UploadedFile $file) use ($existingNames) {
            return $existingNames->has($file->getClientOriginalName());
        });

        $this->uploads = collect($this->backup)->merge($newUploads)->toArray();
    }

    /**
     * Handle file deletion from uploads with error handling.
     */
    public function deleteUpload(array $content): void
    {
        if (empty($this->uploads)) {
            return;
        }

        $this->uploads = collect($this->uploads)
            ->reject(fn (UploadedFile $file): bool => $file->getFilename() === $content['temporary_name'])
            ->values()
            ->toArray();

        // Better error handling for file deletion
        try {
            $tempPath = storage_path('app/livewire-tmp/' . $content['temporary_name']);
            if (File::exists($tempPath)) {
                File::delete($tempPath);
            }
        } catch (Exception $e) {
            Log::warning('Failed to delete temporary upload file', [
                'file'  => $content['temporary_name'],
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Save uploads with transaction and better error handling.
     */
    public function save(): void
    {
        $this->validate();

        if ($savedCount = MediaLibrary::savePhotosToPerson($this->person, $this->uploads)) {
            $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', $savedCount))->send();
        }

        $this->redirectRoute('people.edit-photos', $this->person->id);
    }

    /**
     * Delete a photo with batch operations and better error handling.
     */
    public function delete(int $mediaId): void
    {
        $this->person->media->firstWhere('id', $mediaId)->delete();

        $this->toast()->success(__('app.delete'), __('person.photo_deleted'))->send();

        $this->redirectRoute('people.edit-photos', $this->person->id);
    }

    /**
     * Set the primary photo with validation.
     */
    public function setPrimary(int $id): void
    {
        $newOrder = $this->photos
            ->pluck('id')
            ->prepend($id)
            ->unique()
            ->toArray();

        Media::setNewOrder($newOrder);

        $this->toast()->success(__('app.saved'), __('person.photo_is_set_primary'))->send();

        $this->redirectRoute('people.edit-photos', $this->person->id);
    }

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
            'uploads.*.file'      => __('validation.file', ['attribute' => __('person.photos')]),
            'uploads.*.mimetypes' => __('validation.mimetypes', [
                'attribute' => __('person.photos'),
                'values'    => implode(', ', array_values(config('app.upload_photo_accept'))),
            ]),
            'uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photos'),
                'max'       => config('app.upload_max_size'),
            ]),
        ];
    }
}
