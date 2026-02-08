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
use Illuminate\Support\Number;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Photos extends Component
{
    use Interactions;
    use WithFileUploads;

    public Person $person;

    /** @var array<int, UploadedFile> */
    public array $uploads = [];

    /** @var array<int, UploadedFile> */
    public array $backup = [];

    /**
     * @var Collection<int, array{name: string, extension: string, is_primary: bool, url_original: string, url_large: string, url_medium: string, url_small: string, size: string, name_download: string, path: string}>|null
     */
    public ?Collection $photos = null;

    public function mount(): void
    {
        $this->loadPhotos();
    }

    /**
     * Handle updates to the uploads property.
     * Backs up current uploads before Livewire processes new ones.
     */
    public function updatingUploads(): void
    {
        // Store the current uploads before they get replaced
        $this->backup = $this->uploads;
    }

    /**
     * Process uploaded files and remove duplicates.
     * Merges new uploads with backup and removes duplicates based on original filename.
     */
    public function updatedUploads(): void
    {
        $this->validate();

        if (empty($this->uploads)) {
            return;
        }

        // Merge backup with new uploads
        $allUploads = array_merge($this->backup, $this->uploads);

        // Remove duplicates based on original filename
        $this->uploads = collect($allUploads)
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->values()
            ->toArray();

        // Clear backup after merge
        $this->backup = [];
    }

    /**
     * Handle file deletion from uploads.
     * Removes the specified file from the uploads array and deletes the temporary file.
     *
     * @param  array<string, mixed>  $content  File information containing temporary_name, real_name, extension, size, path, url
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

        // Remove from uploads array
        $this->uploads = collect($this->uploads)
            ->reject(fn (UploadedFile $file): bool => $file->getFilename() === $temporaryName)
            ->values()
            ->toArray();

        // Clean up temporary file
        $this->deleteTemporaryFile($temporaryName);
    }

    /**
     * Save uploads.
     */
    public function save(): void
    {
        $this->validate();

        if (empty($this->uploads)) {
            return;
        }

        try {
            // Additional security check
            foreach ($this->uploads as $upload) {
                if (! $this->isValidImage($upload)) {
                    $this->toast()->error(__('app.error'), __('person.invalid_image_file_detected'))->send();

                    return;
                }
            }

            $personPhotos = new PersonPhotos($this->person);
            $savedCount   = $personPhotos->save($this->uploads);

            if ($savedCount > 0) {
                $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', $savedCount))->send();
                $this->dispatch('photos_updated');
                $this->loadPhotos();
                $this->uploads = [];
                $this->backup  = [];
            }
        } catch (Exception $e) {
            Log::error('Failed to save person photos', [
                'person_id' => $this->person->id,
                'error'     => $e->getMessage(),
            ]);

            $this->toast()->error(__('app.error'), __('person.photos_save_failed'))->send();
        }
    }

    /**
     * Delete a specific photo.
     */
    public function delete(string $photo): void
    {
        try {
            $personPhotos = new PersonPhotos($this->person);

            // Extract index from photo filename
            $index = $this->extractPhotoIndex($photo);

            if ($index === null) {
                throw new Exception('Invalid photo filename format');
            }

            $deleted = $personPhotos->delete($index);

            if ($deleted) {
                $this->toast()->success(__('app.delete'), __('person.photo_deleted'))->send();
                $this->dispatch('photos_updated');
                $this->loadPhotos();
            } else {
                $this->toast()->warning(__('app.warning'), __('person.photo_not_found'))->send();
            }
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
     * Set a photo as primary.
     */
    public function setPrimary(string $photo): void
    {
        try {
            $personPhotos = new PersonPhotos($this->person);

            // Verify photo exists
            if (! $personPhotos->photoExists($photo)) {
                throw new Exception('Photo does not exist');
            }

            $this->person->update(['photo' => $photo]);

            $this->toast()->success(__('app.saved'), __('person.photo_is_set_primary'))->send();
            $this->dispatch('photos_updated');
            $this->loadPhotos();
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

    /**
     * Validation rules.
     *
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        $dimensions = config('app.upload_photo_validation.dimensions');

        return [
            'uploads.*' => [
                'image',
                'mimes:' . config('app.upload_photo_validation.mimes_rule'),
                'max:' . config('app.upload_max_size'),
                sprintf(
                    'dimensions:min_width=%d,min_height=%d,max_width=%d,max_height=%d',
                    $dimensions['min_width'],
                    $dimensions['min_height'],
                    $dimensions['max_width'],
                    $dimensions['max_height']
                ),
            ],
        ];
    }

    /**
     * Validation messages.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        $acceptedFormats = implode(', ', array_values(config('app.upload_photo_accept')));
        $maxSizeMB       = config('app.upload_max_size') / 1024;

        return [
            'uploads.*.image' => __('validation.image', ['attribute' => __('person.photos')]),
            'uploads.*.mimes' => __('validation.mimes', [
                'attribute' => __('person.photos'),
                'values'    => $acceptedFormats,
            ]),
            'uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photos'),
                'max'       => config('app.upload_max_size'),
            ]),
            'uploads.*.dimensions' => __('validation.dimensions', ['attribute' => __('person.photos')]),
        ];
    }

    // -----------------------------------------------------------------------
    // Protected and Private Methods
    // -----------------------------------------------------------------------

    /**
     * Load photos from PersonPhotos class.
     */
    private function loadPhotos(): void
    {
        try {
            $personPhotos = new PersonPhotos($this->person);
            $photosData   = $personPhotos->getAllPhotos();

            $this->photos = collect($photosData)->map(function ($photo) {
                // Add file size if available
                $path = storage_path('app/public/photos/' . $this->person->team_id . '/' . $this->person->id . '/' . $photo['name'] . '.' . $photo['extension']);

                $fileSize = 0;
                if (file_exists($path)) {
                    $fileSizeResult = filesize($path);
                    $fileSize       = $fileSizeResult !== false ? $fileSizeResult : 0;
                }

                return array_merge($photo, [
                    'size'          => Number::fileSize($fileSize, 2),
                    'name_download' => "{$this->person->name} - {$photo['name']}.{$photo['extension']}",
                    'path'          => dirname($path),
                ]);
            })->sortBy('name');
        } catch (Exception $e) {
            Log::error('Failed to load person photos', [
                'person_id' => $this->person->id,
                'error'     => $e->getMessage(),
            ]);

            $this->photos = collect();
        }
    }

    /**
     * Extract photo index from filename.
     * Expects format: {personId}_{index}_{timestamp}
     */
    private function extractPhotoIndex(string $filename): ?int
    {
        $parts = explode('_', $filename);

        if (count($parts) >= 3 && is_numeric($parts[1])) {
            return (int) $parts[1];
        }

        return null;
    }

    /**
     * Delete a temporary file from storage.
     * Safely handles file deletion with error suppression.
     *
     * @param  string  $filename  The temporary filename to delete
     */
    private function deleteTemporaryFile(string $filename): void
    {
        try {
            $path = storage_path('app/livewire-tmp/' . $filename);

            if (file_exists($path)) {
                File::delete($path);
            }
        } catch (Exception $e) {
            Log::warning('Failed to delete temporary file', [
                'file'  => $filename,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Validate that uploaded file is a genuine image
     */
    private function isValidImage(UploadedFile $file): bool
    {
        // Check 1: Verify MIME type matches config
        $mimeType     = $file->getMimeType();
        $allowedMimes = array_keys(config('app.upload_photo_accept'));

        if (! in_array($mimeType, $allowedMimes)) {
            Log::warning('Invalid MIME type detected', [
                'file'    => $file->getClientOriginalName(),
                'mime'    => $mimeType,
                'allowed' => $allowedMimes,
            ]);

            return false;
        }

        // Check 2: Verify file is actually an image using getimagesize
        try {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo === false) {
                Log::warning('File failed getimagesize validation', [
                    'file' => $file->getClientOriginalName(),
                ]);

                return false;
            }

            // Verify the image type matches expected types
            $allowedImageTypes = config('app.upload_photo_validation.image_types');

            if (! in_array($imageInfo[2], $allowedImageTypes)) {
                Log::warning('Image type not allowed', [
                    'file' => $file->getClientOriginalName(),
                    'type' => $imageInfo[2],
                ]);

                return false;
            }
        } catch (Exception $e) {
            Log::error('Error validating image', [
                'file'  => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            return false;
        }

        // Check 3: Verify extension matches allowed types
        $extension         = mb_strtolower($file->getClientOriginalExtension());
        $allowedExtensions = config('app.upload_photo_validation.extensions');

        if (! in_array($extension, $allowedExtensions)) {
            Log::warning('Invalid file extension', [
                'file'      => $file->getClientOriginalName(),
                'extension' => $extension,
            ]);

            return false;
        }

        return true;
    }
}
