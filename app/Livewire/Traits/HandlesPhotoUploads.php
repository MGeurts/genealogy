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

        // Remove duplicates based on original filename
        $this->form->uploads = collect($allUploads)
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
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
     *      *
     * @return array<string, array<int, string|int>>
     */
    protected function getPhotoUploadRules(): array
    {
        return [
            'form.uploads.*' => [
                'image',
                'mimetypes:' . implode(',', array_keys(config('app.upload_photo_accept'))),
                'max:' . config('app.upload_max_size'),
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
        return [
            'form.uploads.*.image'     => __('validation.image', ['attribute' => __('person.photos')]),
            'form.uploads.*.mimetypes' => __('validation.mimetypes', [
                'attribute' => __('person.photos'),
                'values'    => implode(', ', array_values(config('app.upload_photo_accept'))),
            ]),
            'form.uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photos'),
                'max'       => config('app.upload_max_size'),
            ]),
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
