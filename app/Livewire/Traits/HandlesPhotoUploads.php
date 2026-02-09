<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Trait for handling photo uploads in Livewire components.
 * Provides common functionality for upload management and deduplication.
 */
trait HandlesPhotoUploads
{
    /**
     * Handle updates to the uploads property.
     * Backs up current uploads before Livewire processes new ones.
     *
     * Note: This uses 'form.uploads' path for nested form objects
     */
    public function updatingFormUploads(): void
    {
        // Store the current uploads before they get replaced
        $this->form->backup = $this->form->uploads;
    }

    /**
     * Process uploaded files and remove duplicates.
     * Merges new uploads with backup and removes duplicates based on original filename.
     * Also validates each upload for security.
     *
     * Note: This uses 'form.uploads' path for nested form objects
     */
    public function updatedFormUploads(): void
    {
        if (empty($this->form->uploads)) {
            return;
        }

        // Merge backup with new uploads
        $allUploads = array_merge($this->form->backup, $this->form->uploads);

        // Remove duplicates based on original filename AND validate uploads
        $this->form->uploads = collect($allUploads)
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->filter(fn (UploadedFile $file): bool => $this->isValidImageUpload($file))
            ->values()
            ->toArray();

        // Clear backup after merge
        $this->form->backup = [];
    }

    /**
     * Handle file deletion from uploads.
     * Removes the specified file from the uploads array and deletes the temporary file.
     *
     * @param  array{uuid: string, name: string, size: int, url: string, temporaryUrl: string}  $content
     */
    public function deleteUpload(array $content): void
    {
        if (empty($this->form->uploads)) {
            return;
        }

        $temporaryName = $content['temporary_name'] ?? null;

        if (! $temporaryName) {
            return;
        }

        // Remove from uploads array
        $this->form->uploads = collect($this->form->uploads)
            ->filter(fn (UploadedFile $file): bool => $file->getFilename() !== $temporaryName)
            ->values()
            ->toArray();

        // Delete temporary file
        $this->deleteTemporaryFile($temporaryName);
    }

    /**
     * Validate that uploaded file is a genuine image.
     * Performs multiple security checks to prevent malicious uploads.
     *
     * @param  UploadedFile  $file  The file to validate
     * @return bool True if file is valid, false otherwise
     */
    protected function isValidImageUpload(UploadedFile $file): bool
    {
        // Check 1: Verify MIME type matches config
        $mimeType     = $file->getMimeType();
        $allowedMimes = array_keys(config('app.upload_photo_accept'));

        if (! in_array($mimeType, $allowedMimes)) {
            Log::warning('Invalid MIME type detected in upload', [
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
            Log::error('Error validating image upload', [
                'file'  => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            return false;
        }

        // Check 3: Verify extension matches allowed types
        $extension         = mb_strtolower($file->getClientOriginalExtension());
        $allowedExtensions = config('app.upload_photo_validation.extensions');

        if (! in_array($extension, $allowedExtensions)) {
            Log::warning('Invalid file extension in upload', [
                'file'      => $file->getClientOriginalName(),
                'extension' => $extension,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Delete a temporary file from storage.
     * Safely handles file deletion with error suppression.
     *
     * @param  string  $filename  The temporary filename to delete
     */
    protected function deleteTemporaryFile(string $filename): void
    {
        try {
            $path = storage_path('app/livewire-tmp/' . $filename);

            if (file_exists($path)) {
                File::delete($path);
            }
        } catch (Exception $e) {
            Log::warning('Failed to delete temporary upload file', [
                'file'  => $filename,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get validation rules for photo uploads.
     * Returns array of rules based on application configuration.
     *
     * @return array<string, array<int, string|int>>
     */
    protected function getPhotoUploadRules(): array
    {
        $dimensions = config('app.upload_photo_validation.dimensions');

        return [
            'form.uploads.*' => [
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
     * Get validation messages for photo uploads.
     * Returns localized validation messages.
     *
     * @return array<string, string>
     */
    protected function getPhotoUploadMessages(): array
    {
        $acceptedFormats = implode(', ', array_values(config('app.upload_photo_accept')));

        return [
            'form.uploads.*.image' => __('validation.image', ['attribute' => __('person.photos')]),
            'form.uploads.*.mimes' => __('validation.mimes', [
                'attribute' => __('person.photos'),
                'values'    => $acceptedFormats,
            ]),
            'form.uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photos'),
                'max'       => config('app.upload_max_size'),
            ]),
            'form.uploads.*.dimensions' => __('validation.dimensions', ['attribute' => __('person.photos')]),
        ];
    }

    /**
     * Get validation attributes for photo uploads.
     * Returns localized attribute names.
     *
     * @return array<string, string>
     */
    protected function getPhotoUploadAttributes(): array
    {
        return [
            'form.uploads' => __('person.photos'),
        ];
    }
}
