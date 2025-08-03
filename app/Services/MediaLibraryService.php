<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MediaCollection;
use App\Models\Person;
use Illuminate\Support\Collection;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use stdClass;

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

    public function loadTreePeopleImageUrl(Collection $collection): Collection
    {
        $personIds = $collection->pluck('id')->toArray();

        $photos = Media::where('model_type', Person::class)
            ->whereIn('model_id', $personIds)
            ->where('collection_name', MediaCollection::PHOTO->value)
            ->where('order_column', 1)
            ->get()
            ->keyBy('model_id');

        return $collection->each(function (stdClass $person) use ($photos) {
            if ($photo = $photos->get($person->id)) {
                $person->photo_url = $photo->getTemporaryUrl(now()->addHour());
            }
        });
    }
}
