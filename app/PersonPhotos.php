<?php

declare(strict_types=1);

namespace App;

use App\Models\Person;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PersonPhotos
{
    protected Person $person;

    protected ImageManager $imageManager;

    protected array $config;

    public function __construct(Person $person)
    {
        $this->person = $person;

        $this->imageManager = new ImageManager(new Driver);

        $this->config = config('app.image_upload');
    }

    public function save(array $photos): void
    {
        if (empty($photos)) {
            return;
        }

        $this->ensureDirectoriesExist();

        $lastIndex = $this->getLastImageIndex();

        foreach ($photos as $photo) {
            $this->savePhoto($photo, ++$lastIndex);
        }

        $this->cleanupTemporaryFiles();
    }

    protected function ensureDirectoriesExist(): void
    {
        $teamId = (string) $this->person->team_id;

        foreach (config('app.photo_folders') as $folder) {
            if (! Storage::disk($folder)->exists($teamId)) {
                Storage::disk($folder)->makeDirectory($teamId);
            }
        }
    }

    protected function getLastImageIndex(): int
    {
        $files = File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp');

        if ($files) {
            $lastFile = last($files);

            return (int) substr($lastFile, strpos($lastFile, '_') + 1, strrpos($lastFile, '_') - strpos($lastFile, '_') - 1);
        }

        return 0;
    }

    protected function savePhoto($photo, int $index): void
    {
        $timestamp = now()->format('YmdHis');
        $imageName = "{$this->person->id}_" . str_pad((string) $index, 3, '0', STR_PAD_LEFT) . "_{$timestamp}.{$this->config['type']}";

        if ($this->config['add_watermark']) {
            $this->processAndSaveImage($photo, $imageName, true);
        } else {
            $this->processAndSaveImage($photo, $imageName, false);
        }

        if (empty($this->person->photo)) {
            $this->person->update(['photo' => $imageName]);
        }
    }

    protected function processAndSaveImage($photo, string $imageName, bool $addWatermark): void
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

            if ($addWatermark) {
                $image->place(public_path('img/watermark.png'), 'bottom-left', 5, 5);
            }

            $image
                ->toWebp(quality: $this->config['quality'])
                ->save(storage_path("app/public/{$disk}/{$this->person->team_id}/{$imageName}"));
        }
    }

    protected function cleanupTemporaryFiles(): void
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
