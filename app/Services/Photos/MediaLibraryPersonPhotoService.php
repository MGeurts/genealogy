<?php

declare(strict_types=1);

namespace App\Services\Photos;

use App\Contracts\PersonPhotoServiceInterface;
use App\Models\Person;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

/**
 * Media Library-backed implementation of the person photo service.
 *
 * This implementation uses the Spatie Media Library `photos` collection on the
 * Person model so the rest of the application can remain agnostic of how
 * photos are stored.
 */
class MediaLibraryPersonPhotoService implements PersonPhotoServiceInterface
{
    public function save(Person $person, array $photos): ?int
    {
        $created = 0;

        foreach ($photos as $photo) {
            /** @var Media $media */
            $media = $person
                ->addMedia($photo)
                ->toMediaCollection('photos', 'photos');

            if (! $person->photo) {
                $person->photo = (string) $media->id;
                $person->save();
            }

            $created++;
        }

        return $created > 0 ? $created : null;
    }

    public function getAllPhotos(Person $person): array
    {
        return $person->getMedia('photos')
            ->map(function (Media $media) use ($person): array {
                $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);
                $id        = (string) $media->id;

                return [
                    'id'           => $id,
                    'name'         => $media->name,
                    'extension'    => $extension,
                    'is_primary'   => $id === $person->photo,
                    'url_original' => $this->getSignedUrl($media, 'original'),
                    'url_large'    => $this->getSignedUrl($media, 'large'),
                    'url_medium'   => $this->getSignedUrl($media, 'medium'),
                    'url_small'    => $this->getSignedUrl($media, 'small'),
                ];
            })
            ->values()
            ->all();
    }

    public function getPhotoUrl(Person $person, string $photoId, string $variant = 'medium'): ?string
    {
        $media = $person->getMedia('photos')->firstWhere('id', (int) $photoId);

        if (! $media) {
            return null;
        }

        return $this->getSignedUrl($media, $variant);
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
        $media = $person->getMedia('photos')->firstWhere('id', (int) $photoId);

        if (! $media) {
            return false;
        }

        $wasPrimary = $person->photo === (string) $media->id;

        $media->delete();

        if ($wasPrimary) {
            $this->setNewPrimaryPhoto($person);
        }

        return true;
    }

    public function deleteAll(Person $person): bool
    {
        $person->clearMediaCollection('photos');
        $person->photo = null;
        $person->save();

        return true;
    }

    public function photoExists(Person $person, string $photoId): bool
    {
        return $person->getMedia('photos')->contains('id', (int) $photoId);
    }

    public function setPrimary(Person $person, string $photoId): void
    {
        if (! $this->photoExists($person, $photoId)) {
            return;
        }

        $person->update(['photo' => $photoId]);
    }

    public function setNewPrimaryPhoto(Person $person): void
    {
        /** @var Media|null $first */
        $first = $person->getMedia('photos')->first();

        $person->photo = $first ? (string) $first->id : null;
        $person->save();
    }

    public function getGalleryImages(Person $person): array
    {
        return $person->getMedia('photos')
            ->map(function (Media $media): array {
                $filename = pathinfo($media->file_name, PATHINFO_FILENAME);

                return [
                    'id'       => (string) $media->id,
                    'filename' => $filename,
                    'small'    => $this->getSignedUrl($media, 'small'),
                    'medium'   => $this->getSignedUrl($media, 'medium'),
                    'large'    => $this->getSignedUrl($media, 'large'),
                    'original' => $this->getSignedUrl($media, 'original'),
                ];
            })
            ->values()
            ->all();
    }

    public function getOriginalPhotosForExport(Person $person): array
    {
        return $person->getMedia('photos')
            ->map(function (Media $media): array {
                $path               = $media->getPath();
                $filename           = basename($path);
                $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);

                return [
                    'filename'       => $filenameWithoutExt,
                    'file_reference' => $filename,
                    'mime_type'      => $media->mime_type ?: 'application/octet-stream',
                    'disk_path'      => $path,
                    'url'            => $this->getSignedUrl($media, 'original'),
                ];
            })
            ->values()
            ->all();
    }

    public function getAbsolutePath(string $diskPath): string
    {
        return Storage::disk('photos')->path($diskPath);
    }

    public function cleanupOnDelete(Person $person): void
    {
        $person->clearMediaCollection('photos');
    }

    public function ensureTeamDirectoryExists(int $teamId): void
    {
        // Spatie Media Library manages directories automatically for the configured disk.
    }

    public function deleteTeamDirectory(int $teamId): void
    {
        Person::query()
            ->where('team_id', $teamId)
            ->each(function (Person $person): void {
                $person->clearMediaCollection('photos');
            });
    }

    public function getMediumImageFilenames(Person $person): array
    {
        return $person->getMedia('photos')
            ->map(function (Media $media): string {
                $path = $media->getPath('medium');

                return basename($path);
            })
            ->values()
            ->all();
    }

    public function getPhotoUrlFromAttributes(
        int $teamId,
        int $personId,
        string $photoIdentifier,
        string $variant = 'small',
    ): ?string {
        $media = Media::find((int) $photoIdentifier);

        if (! $media) {
            return null;
        }

        return $this->getSignedUrl($media, $variant);
    }

    private function getSignedUrl(Media $media, string $variant): string
    {
        $expiryMinutes = (int) config('app.photo_signed_url_ttl', 60);
        $expiresAt     = now()->addMinutes($expiryMinutes);
        $conversion    = $variant === 'original' ? '' : $variant;

        try {
            return $media->getTemporaryUrl($expiresAt, $conversion);
        } catch (Throwable $e) {
            return $conversion === '' ? $media->getUrl() : $media->getUrl($conversion);
        }
    }
}
