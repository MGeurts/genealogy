<?php

declare(strict_types=1);

namespace App;

use App\Models\Person;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Throwable;

final class PersonPhotos
{
    public function __construct(
        private readonly Person $person,
        private readonly ImageManager $imageManager = new ImageManager(new Driver()),
        private ?array $uploadConfig = null,
        private ?array $photoFolders = null
    ) {
        $this->uploadConfig = $this->uploadConfig ?? config('app.upload_photo');
        $this->photoFolders = $this->photoFolders ?? config('app.photo_folders', []);
    }

    /**
     * Save multiple photos for the person.
     *
     * @param  array<int, UploadedFile|string>  $photos
     * @return int|null Number of photos saved, or null if none
     */
    public function save(array $photos): ?int
    {
        if (empty($photos)) {
            return null;
        }

        $this->ensureDirectoriesExist();

        $lastIndex = $this->getLastImageIndex();

        foreach ($photos as $photo) {
            $lastIndex++;
            $this->savePhoto($photo, $lastIndex);
        }

        $this->cleanupTemporaryFiles();

        return count($photos);
    }

    /**
     * Ensure all configured photo folders exist for the team.
     */
    private function ensureDirectoriesExist(): void
    {
        $teamId = (string) $this->person->team_id;

        foreach ($this->photoFolders as $folder) {
            $disk = Storage::disk($folder);

            if (! $disk->exists($teamId)) {
                $disk->makeDirectory($teamId);
            }
        }
    }

    /**
     * Get the highest index number used for this person's images.
     */
    private function getLastImageIndex(): int
    {
        $files = File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp');

        if ($files) {
            $lastFile = basename(last($files));

            if (preg_match('/^' . $this->person->id . '_(\d+)_/', $lastFile, $matches)) {
                return (int) $matches[1];
            }
        }

        return 0;
    }

    /**
     * Save a single photo for the person.
     */
    private function savePhoto(UploadedFile|string $photo, int $index): void
    {
        $imageName = sprintf(
            '%s_%03d_%s.%s',
            $this->person->id,
            $index,
            now()->format('YmdHis'),
            $this->uploadConfig['type']
        );

        try {
            $this->processAndSaveImage($photo, $imageName);

            if (empty($this->person->photo)) {
                $this->person->update(['photo' => $imageName]);
            }
        } catch (Throwable $e) {
            Log::error("Failed to save photo for person {$this->person->id}: {$e->getMessage()}", [
                'exception' => $e,
            ]);
        }
    }

    /**
     * Process and save an image in multiple sizes.
     */
    private function processAndSaveImage(UploadedFile|string $photo, string $imageName): void
    {
        $paths = [
            'photos' => [
                'width'  => $this->uploadConfig['max_width'],
                'height' => $this->uploadConfig['max_height'],
            ],
            'photos-096' => [
                'width'  => 96,
                'height' => null,
            ],
            'photos-384' => [
                'width'  => 384,
                'height' => null,
            ],
        ];

        $original = $this->imageManager->read($photo);

        foreach ($paths as $disk => $dimensions) {
            $image = clone $original;

            $image->scaleDown(
                width: $dimensions['width'],
                height: $dimensions['height']
            );

            $this->applyWatermark($image);

            $image
                ->toWebp(quality: $this->uploadConfig['quality'])
                ->save(Storage::disk($disk)->path("{$this->person->team_id}/{$imageName}"));
        }
    }

    /**
     * Apply watermark if enabled in config.
     */
    private function applyWatermark($image): void
    {
        if (! $this->uploadConfig['add_watermark']) {
            return;
        }

        $path = public_path('img/watermark.png');

        if (! file_exists($path)) {
            Log::warning("Watermark file missing: {$path}");

            return;
        }

        $image->place($path, 'bottom-left', 5, 5);
    }

    /**
     * Cleanup old Livewire temporary files.
     */
    private function cleanupTemporaryFiles(): void
    {
        $cutoff = now()->subDay()->timestamp;

        $files = array_filter(
            Storage::disk('local')->files('livewire-tmp'),
            fn ($file) => Storage::disk('local')->lastModified($file) < $cutoff
        );

        if ($files) {
            Storage::disk('local')->delete($files);
        }
    }
}
