<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Models\Person;
use App\PersonPhotos;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\View\View;
use InvalidArgumentException;
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
        $this->loadPhotosOptimized();
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

        if (empty($this->uploads)) {
            return;
        }

        try {
            $personPhotos = new PersonPhotos($this->person);
            $savedCount   = $personPhotos->save($this->uploads);

            $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', $savedCount))->send();

            $this->dispatch('photos_updated');

            $this->loadPhotosOptimized();

            $this->uploads = [];
        } catch (Exception $e) {
            Log::error('Failed to save person photos', [
                'person_id' => $this->person->id,
                'error'     => $e->getMessage(),
            ]);

            $this->toast()->error(__('app.error'), __('person.photos_save_failed'))->send();
        }
    }

    /**
     * Delete a photo with batch operations and better error handling.
     */
    public function delete(string $photo): void
    {
        try {
            $wasPrimary = ($photo === $this->person->photo);

            // Batch delete operations
            $this->deletePhotoFilesBatch($photo, $this->person->team_id);

            if ($wasPrimary) {
                $this->setNewPrimaryPhotoOptimized();
            }

            $this->toast()->success(__('app.delete'), __('person.photo_deleted'))->send();

            $this->dispatch('photos_updated');

            $this->loadPhotosOptimized();
        } catch (Exception $e) {
            Log::error('Failed to delete person photo', [
                'person_id' => $this->person->id,
                'photo'     => $photo,
                'error'     => $e->getMessage(),
            ]);

            $this->toast()->error(__('app.error'), __('person.photo_delete_failed'))->send();
        }
    }

    /**
     * Set the primary photo with validation.
     */
    public function setPrimary(string $photo): void
    {
        try {
            // Verify photo exists before setting as primary
            if (! $this->photoExists($photo)) {
                throw new InvalidArgumentException('Photo does not exist');
            }

            $this->person->update(['photo' => $photo]);

            $this->toast()->success(__('app.saved'), __('person.photo_is_set_primary'))->send();

            $this->dispatch('photos_updated');
        } catch (Exception $e) {
            Log::error('Failed to set primary photo', [
                'person_id' => $this->person->id,
                'photo'     => $photo,
                'error'     => $e->getMessage(),
            ]);

            $this->toast()->error(__('app.error'), __('person.photo_set_primary_failed'))->send();
        }
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

    // -----------------------------------------------------------------------

    /**
     * Load photos with optimized file operations.
     */
    private function loadPhotosOptimized(): void
    {
        $this->photos = collect();

        try {
            $finder = $this->getPersonPhotosFinder();

            if (! $finder->hasResults()) {
                return;
            }

            foreach ($finder as $file) {
                $this->photos->push($this->mapPhotoData($file));
            }

            $this->photos = $this->photos->sortBy('name');
        } catch (Exception $e) {
            Log::error('Failed to load person photos', [
                'person_id' => $this->person->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Retrieve person photos with error handling.
     */
    private function getPersonPhotosFinder(): Finder
    {
        $teamId     = $this->person->team_id;
        $photosPath = public_path("storage/photos/{$teamId}");

        if (! File::exists($photosPath)) {
            File::makeDirectory($photosPath, 0755, true);
        }

        return Finder::create()
            ->in($photosPath)
            ->name("{$this->person->id}_*.webp")
            ->files();
    }

    /**
     * Map photo data with error handling.
     */
    private function mapPhotoData(SplFileInfo $file): array
    {
        $teamId   = $this->person->team_id;
        $filename = $file->getFilename();

        return [
            'name'          => $filename,
            'name_download' => "{$this->person->name} - {$filename}",
            'extension'     => $file->getExtension(),
            'size'          => Number::fileSize($file->getSize(), 2),
            'path'          => $file->getPath(),
            'url'           => Storage::url("photos-384/{$teamId}/{$filename}"),
            'url_original'  => Storage::url("photos/{$teamId}/{$filename}"),
            'is_primary'    => $filename === $this->person->photo,
        ];
    }

    /**
     * Delete photo files from all storage locations in batch.
     */
    private function deletePhotoFilesBatch(string $photo, int $teamId): void
    {
        $folders          = config('app.photo_folders', ['public']);
        $deleteOperations = [];

        foreach ($folders as $folder) {
            $deleteOperations[] = fn (): bool => Storage::disk($folder)->delete("{$teamId}/{$photo}");
        }

        // Execute all deletions
        foreach ($deleteOperations as $operation) {
            try {
                $operation();
            } catch (Exception $e) {
                Log::warning('Failed to delete photo from storage', [
                    'photo'   => $photo,
                    'team_id' => $teamId,
                    'error'   => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Set a new primary photo with better performance.
     */
    private function setNewPrimaryPhotoOptimized(): void
    {
        try {
            // Use existing photos collection if available to avoid file system call
            if ($this->photos && $this->photos->isNotEmpty()) {
                $firstPhoto = $this->photos->first()['name'] ?? null;
            } else {
                // Fallback to file system check
                $files      = File::glob(public_path("storage/photos/{$this->person->team_id}/{$this->person->id}_*.webp"));
                $firstPhoto = $files ? basename((string) $files[0]) : null;
            }

            $this->person->update(['photo' => $firstPhoto]);
        } catch (Exception $e) {
            Log::error('Failed to set new primary photo', [
                'person_id' => $this->person->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check if a photo exists.
     */
    private function photoExists(string $photo): bool
    {
        $path = public_path("storage/photos/{$this->person->team_id}/{$photo}");

        return File::exists($path);
    }
}
