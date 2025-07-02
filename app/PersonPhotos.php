<?php

declare(strict_types=1);

namespace App;

use App\Models\Person;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

final class PersonPhotos
{
    private readonly ImageManager $imageManager;

    private array $config;

    public function __construct(private readonly Person $person)
    {
        $this->imageManager = new ImageManager(new Driver);

        $this->config = config('app.upload_photo');
    }

    public function save(array $photos): ?int
    {
        if (empty($photos)) {
            return null;
        }

        $this->ensureDirectoriesExist();

        $lastIndex = $this->getLastImageIndex();

        foreach ($photos as $photo) {
            $this->savePhoto($photo, ++$lastIndex);
        }

        $this->cleanupTemporaryFiles();

        return count($photos);
    }

    private function ensureDirectoriesExist(): void
    {
        $teamId = (string) $this->person->team_id;

        foreach (config('app.photo_folders') as $folder) {
            $disk = Storage::disk($folder);

            if (! $disk->exists($teamId)) {
                $disk->makeDirectory($teamId);
            }
        }
    }

    private function getLastImageIndex(): int
    {
        $files = File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp');

        if ($files) {
            $lastFile = last($files);

            return (int) mb_substr(
                (string) $lastFile,
                mb_strpos((string) $lastFile, '_') + 1,
                mb_strrpos((string) $lastFile, '_') - mb_strpos((string) $lastFile, '_') - 1
            );
        }

        return 0;
    }

    private function savePhoto($photo, int $index): void
    {
        $imageName = sprintf(
            '%s_%03d_%s.%s',
            $this->person->id,
            $index,
            now()->format('YmdHis'),
            $this->config['type']
        );

        $this->processAndSaveImage(
            photo: $photo,
            imageName: $imageName
        );

        if (empty($this->person->photo)) {
            $this->person->update(['photo' => $imageName]);
        }
    }

    private function processAndSaveImage($photo, string $imageName): void
    {
        $paths = [
            'photos' => [
                'width'  => $this->config['max_width'],
                'height' => $this->config['max_height'],
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

        foreach ($paths as $disk => $dimensions) {
            $image = $this->imageManager
                ->read($photo)
                ->scaleDown(
                    width: $dimensions['width'],
                    height: $dimensions['height']
                );

            if ($this->config['add_watermark']) {
                $image->place(public_path('img/watermark.png'), 'bottom-left', 5, 5);
            }

            $image
                ->toWebp(quality: $this->config['quality'])
                ->save(storage_path("app/public/{$disk}/{$this->person->team_id}/{$imageName}"));
        }
    }

    private function cleanupTemporaryFiles(): void
    {
        $oneDayAgo = now()->subDay()->timestamp;

        defer(function () use ($oneDayAgo): void {
            foreach (Storage::files('livewire-tmp') as $file) {
                if (Storage::lastModified($file) < $oneDayAgo) {
                    Storage::delete($file);
                }
            }
        });
    }
}
