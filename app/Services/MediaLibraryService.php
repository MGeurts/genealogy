<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MediaCollection;
use App\Models\Person;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaLibraryService
{
    /**
     * Save photos to a person.
     *
     * @param  array<TemporaryUploadedFile>  $uploads
     */
    public function savePhotosToPerson(Person $person, array $uploads): int
    {
        foreach ($uploads as $upload) {
            $person
                ->addMediaFromDisk($upload->getClientOriginalPath())
                ->setFileName($upload->getClientOriginalName())
                ->toMediaCollection(MediaCollection::PHOTO->value);
        }

        return count($uploads);
    }
}
