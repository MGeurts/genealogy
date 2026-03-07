<?php

declare(strict_types=1);

namespace App\Services\Photos;

use App\Contracts\PersonPhotoServiceInterface;
use App\Models\Person;
use App\PersonPhotos;
use Illuminate\Support\Facades\Storage;

/**
 * Filesystem-backed implementation of the person photo service.
 *
 * This implementation composes the existing PersonPhotos class and centralizes
 * all direct filesystem access for person photos. It mirrors the current
 * behaviour so callers can switch to alternative drivers without changing
 * their own code.
 */
class CustomPersonPhotoService implements PersonPhotoServiceInterface
{
    public function save(Person $person, array $photos): ?int
    {
        $personPhotos = new PersonPhotos($person);

        /** @var array<int, mixed> $photos */
        return $personPhotos->save($photos);
    }

    public function getAllPhotos(Person $person): array
    {
        $personPhotos = new PersonPhotos($person);
        $photos       = $personPhotos->getAllPhotos();

        return array_map(
            static function (array $photo): array {
                $photo['id'] = $photo['name'];

                return $photo;
            },
            $photos,
        );
    }

    public function getPhotoUrl(Person $person, string $photoId, string $variant = 'medium'): ?string
    {
        if ($variant === 'original') {
            $directory = "{$person->team_id}/{$person->id}";

            if (! Storage::disk('photos')->exists($directory)) {
                return null;
            }

            $files = Storage::disk('photos')->files($directory);

            foreach ($files as $file) {
                $basename = basename($file);

                if ($this->isOriginalFilenameMatch($basename, $photoId)) {
                    return Storage::disk('photos')->url($file);
                }
            }

            return null;
        }

        $path = $this->buildVariantPath($person->team_id, $person->id, $photoId, $variant);

        if (! Storage::disk('photos')->exists($path)) {
            return null;
        }

        return Storage::disk('photos')->url($path);
    }

    public function getPrimaryPhotoUrl(Person $person, string $variant = 'medium'): ?string
    {
        if (! $person->photo) {
            return null;
        }

        return $this->getPhotoUrl($person, $person->photo, $variant);
    }

    public function delete(Person $person, string $photoId): bool
    {
        $index = $this->extractPhotoIndex($photoId);

        if ($index === null) {
            return false;
        }

        $personPhotos = new PersonPhotos($person);

        return $personPhotos->delete($index);
    }

    public function deleteAll(Person $person): bool
    {
        $personPhotos = new PersonPhotos($person);

        return $personPhotos->deleteAll();
    }

    public function photoExists(Person $person, string $photoId): bool
    {
        $personPhotos = new PersonPhotos($person);

        return $personPhotos->photoExists($photoId);
    }

    public function setPrimary(Person $person, string $photoId): void
    {
        $personPhotos = new PersonPhotos($person);

        if (! $personPhotos->photoExists($photoId)) {
            return;
        }

        $person->update(['photo' => $photoId]);
    }

    public function setNewPrimaryPhoto(Person $person): void
    {
        $personPhotos = new PersonPhotos($person);
        $personPhotos->setNewPrimaryPhoto();
    }

    public function getGalleryImages(Person $person): array
    {
        $directory = "{$person->team_id}/{$person->id}";

        if (! Storage::disk('photos')->exists($directory)) {
            return [];
        }

        $allFiles = Storage::disk('photos')->files($directory);

        $images = collect($allFiles)
            ->filter(function (string $file): bool {
                $basename = basename($file);

                return ! str_contains($basename, '_large.')
                    && ! str_contains($basename, '_medium.')
                    && ! str_contains($basename, '_small.');
            })
            ->map(function (string $originalFile) use ($directory): array {
                $baseName           = basename($originalFile);
                $filenameWithoutExt = pathinfo($baseName, PATHINFO_FILENAME);

                $largeFile  = $directory . '/' . $filenameWithoutExt . '_large.webp';
                $mediumFile = $directory . '/' . $filenameWithoutExt . '_medium.webp';
                $smallFile  = $directory . '/' . $filenameWithoutExt . '_small.webp';

                return [
                    'id'       => $filenameWithoutExt,
                    'filename' => $filenameWithoutExt,
                    'small'    => Storage::disk('photos')->exists($smallFile) ? Storage::disk('photos')->url($smallFile) : null,
                    'medium'   => Storage::disk('photos')->exists($mediumFile) ? Storage::disk('photos')->url($mediumFile) : null,
                    'large'    => Storage::disk('photos')->exists($largeFile) ? Storage::disk('photos')->url($largeFile) : null,
                    'original' => Storage::disk('photos')->url($originalFile),
                ];
            })
            ->sortBy('filename')
            ->values()
            ->toArray();

        return $images;
    }

    public function getOriginalPhotosForExport(Person $person): array
    {
        $directory = "{$person->team_id}/{$person->id}";

        if (! Storage::disk('photos')->exists($directory)) {
            return [];
        }

        $allFiles = Storage::disk('photos')->files($directory);

        $images = collect($allFiles)
            ->filter(function (string $file): bool {
                return $this->isOriginalFile($file);
            })
            ->map(function (string $originalFile): array {
                $filename           = basename($originalFile);
                $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
                $mimeType           = Storage::disk('photos')->mimeType($originalFile);

                return [
                    'filename'       => $filenameWithoutExt,
                    'file_reference' => $filename,
                    'mime_type'      => $mimeType ?: 'application/octet-stream',
                    'disk_path'      => $originalFile,
                    'url'            => Storage::disk('photos')->url($originalFile),
                ];
            })
            ->sortBy('filename')
            ->values()
            ->toArray();

        return $images;
    }

    public function getAbsolutePath(string $diskPath): string
    {
        return Storage::disk('photos')->path($diskPath);
    }

    public function cleanupOnDelete(Person $person): void
    {
        Storage::disk('photos')->deleteDirectory($person->team_id . '/' . $person->id);
    }

    public function ensureTeamDirectoryExists(int $teamId): void
    {
        $disk = Storage::disk('photos');
        $path = (string) $teamId;

        if (! $disk->exists($path)) {
            $disk->makeDirectory($path);
        }
    }

    public function deleteTeamDirectory(int $teamId): void
    {
        $disk = Storage::disk('photos');
        $path = (string) $teamId;

        if ($disk->exists($path)) {
            $disk->deleteDirectory($path);
        }
    }

    public function getMediumImageFilenames(Person $person): array
    {
        $disk       = Storage::disk('photos');
        $personPath = "{$person->team_id}/{$person->id}";

        if (! $disk->exists($personPath)) {
            return [];
        }

        $allFiles = collect($disk->files($personPath));

        return $allFiles
            ->filter(fn (string $file): bool => str_starts_with(basename($file), "{$person->id}_") && str_ends_with($file, '_medium.webp'))
            ->map(fn (string $file): string => basename($file))
            ->values()
            ->all();
    }

    public function getPhotoUrlFromAttributes(
        int $teamId,
        int $personId,
        string $photoIdentifier,
        string $variant = 'small',
    ): ?string {
        $path = $this->buildVariantPath($teamId, $personId, $photoIdentifier, $variant);

        if (! Storage::disk('photos')->exists($path)) {
            return null;
        }

        return Storage::disk('photos')->url($path);
    }

    private function buildVariantPath(int $teamId, int $personId, string $photoId, string $variant): string
    {
        $suffix = $variant === 'original' ? '' : '_' . $variant . '.webp';

        if ($variant === 'original') {
            return "{$teamId}/{$personId}/{$photoId}";
        }

        return "{$teamId}/{$personId}/{$photoId}{$suffix}";
    }

    private function extractPhotoIndex(string $filename): ?int
    {
        $parts = explode('_', $filename);

        if (count($parts) >= 3 && is_numeric($parts[1])) {
            return (int) $parts[1];
        }

        return null;
    }

    private function isOriginalFile(string $file): bool
    {
        $filename = pathinfo($file, PATHINFO_FILENAME);

        return ! str_ends_with($filename, '_large')
            && ! str_ends_with($filename, '_medium')
            && ! str_ends_with($filename, '_small');
    }

    private function isOriginalFilenameMatch(string $basename, string $photoId): bool
    {
        $filenameWithoutExt = pathinfo($basename, PATHINFO_FILENAME);

        return $filenameWithoutExt === $photoId
            && ! str_contains($basename, '_large.')
            && ! str_contains($basename, '_medium.')
            && ! str_contains($basename, '_small.');
    }
}
