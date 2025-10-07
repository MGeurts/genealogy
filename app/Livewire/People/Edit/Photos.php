<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Models\Person;
use App\PersonPhotos;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\View\View;
use InvalidArgumentException;
use Livewire\Component;
use Livewire\WithFileUploads;
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
        $newUploads    = collect($this->uploads)->reject(
            fn (UploadedFile $file) => $existingNames->has($file->getClientOriginalName())
        );

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

        $temporaryName = $content['temporary_name'] ?? null;
        if (! $temporaryName) {
            return;
        }

        $this->uploads = collect($this->uploads)
            ->reject(fn (UploadedFile $file): bool => $file->getFilename() === $content['temporary_name'])
            ->values()
            ->toArray();

        // Clean up temporary file
        try {
            $tempPath = storage_path('app/livewire-tmp/' . ($content['temporary_name'] ?? ''));
            if ($tempPath && file_exists($tempPath)) {
                unlink($tempPath);
            }
        } catch (Exception $e) {
            Log::warning('Failed to delete temporary upload file', [
                'file'  => $content['temporary_name'] ?? null,
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

            $this->loadPhotos();

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

            // delete the photo variants
            $this->deletePhotoVariants($photo, $this->person->team_id, $this->person->id);

            if ($wasPrimary) {
                $this->setNewPrimaryPhoto();
            }

            $this->toast()->success(__('app.delete'), __('person.photo_deleted'))->send();

            $this->dispatch('photos_updated');

            $this->loadPhotos();
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
     * Load photos.
     * Only processes original photos, provides URLs for all versions (small, medium, large, original).
     */
    private function loadPhotos(): void
    {
        $this->photos = collect();

        try {
            $disk       = Storage::disk('photos');
            $personPath = "{$this->person->team_id}/{$this->person->id}";

            if (! $disk->exists($personPath)) {
                return;
            }

            $files = $disk->files($personPath);

            foreach ($files as $file) {
                if (! $this->isOriginalPhoto(basename($file))) {
                    continue;
                }

                $this->photos->push($this->mapPhotoData($file, $disk));
            }

            $this->photos = $this->photos->sortBy('name');
        } catch (Exception $e) {
            Log::error('Failed to load person photos', [
                'team_id'   => $this->person->team_id,
                'person_id' => $this->person->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Map photo data
     * Returns URLs for all versions: small, medium, large, and original.
     */
    private function mapPhotoData(string $filePath, $disk): array
    {
        $basename = basename($filePath);
        $filename = pathinfo($basename, PATHINFO_FILENAME);
        $basePath = "{$this->person->team_id}/{$this->person->id}";

        // Get file size efficiently with fallback
        $fileSize = 0;
        try {
            $fileSize = $disk->size($filePath);
        } catch (Exception) {
            // Silently continue with 0 size if file size cannot be determined
        }

        return [
            'name'          => $filename,
            'name_download' => "{$this->person->name} - {$basename}",
            'extension'     => pathinfo($basename, PATHINFO_EXTENSION),
            'size'          => Number::fileSize($fileSize, 2),
            'path'          => $disk->path("{$basePath}"),
            'url_original'  => $disk->url("{$basePath}/{$basename}"),
            'url_large'     => $disk->url("{$basePath}/{$filename}_large.webp"),
            'url_medium'    => $disk->url("{$basePath}/{$filename}_medium.webp"),
            'url_small'     => $disk->url("{$basePath}/{$filename}_small.webp"),
            'is_primary'    => $filename === $this->person->photo,
        ];
    }

    /**
     * Delete photo files.
     * Removes original and all size variants (large, medium, small).
     */
    private function deletePhotoVariants(string $photo, int $teamId, int $personId): void
    {
        $disk       = Storage::disk('photos');
        $personPath = "{$teamId}/{$personId}";

        try {
            // Get all files in the person's directory
            if (! $disk->exists($personPath)) {
                return;
            }

            $files = $disk->files($personPath);

            // Delete all files that match this photo (original + variants)
            foreach ($files as $file) {
                $basename = basename($file);
                $filebase = pathinfo($basename, PATHINFO_FILENAME);

                // Remove size suffix if present to get the base filename
                $filebase = preg_replace('/_(large|medium|small)$/', '', $filebase);

                // If this file belongs to the photo we're deleting, remove it
                if ($filebase === $photo) {
                    try {
                        $disk->delete($file);
                    } catch (Exception $e) {
                        Log::warning('Failed to delete photo file from storage', [
                            'photo'     => $photo,
                            'team_id'   => $teamId,
                            'person_id' => $personId,
                            'file'      => $file,
                            'error'     => $e->getMessage(),
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to delete photo variants', [
                'photo'     => $photo,
                'team_id'   => $teamId,
                'person_id' => $personId,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Set a new primary photo.
     */
    private function setNewPrimaryPhoto(): void
    {
        try {
            $firstPhoto = null;

            // Use existing photos collection if available (most efficient)
            if ($this->photos?->isNotEmpty()) {
                $firstPhoto = $this->photos->first()['name'] ?? null;
            } else {
                // Fallback to file system check - find first original photo
                $disk       = Storage::disk('photos');
                $personPath = "{$this->person->team_id}/{$this->person->id}";

                if ($disk->exists($personPath)) {
                    $files = $disk->files($personPath);

                    foreach ($files as $file) {
                        $basename = basename($file);

                        if (! $this->isOriginalPhoto($basename)) {
                            continue;
                        }

                        // Found first original photo
                        $firstPhoto = pathinfo($basename, PATHINFO_FILENAME);
                        break;
                    }
                }
            }

            $this->person->update(['photo' => $firstPhoto]);
        } catch (Exception $e) {
            Log::error('Failed to set new primary photo', [
                'team_id'   => $this->person->team_id,
                'person_id' => $this->person->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check if a filename represents an original photo (not a sized version).
     */
    private function isOriginalPhoto(string $basename): bool
    {
        return ! str_contains($basename, '_large.')
            && ! str_contains($basename, '_medium.')
            && ! str_contains($basename, '_small.');
    }

    /**
     * Check if a photo exists.
     */
    private function photoExists(string $photo): bool
    {
        $disk       = Storage::disk('photos');
        $personPath = "{$this->person->team_id}/{$this->person->id}";

        if (! $disk->exists($personPath)) {
            return false;
        }

        $files = $disk->files($personPath);

        // Check if any file matches the photo filename (regardless of extension)
        foreach ($files as $file) {
            $basename = basename($file);
            $filebase = pathinfo($basename, PATHINFO_FILENAME);

            // This is an original file and matches our photo name
            if ($filebase === $photo && $this->isOriginalPhoto($basename)) {
                return true;
            }
        }

        return false;
    }
}
