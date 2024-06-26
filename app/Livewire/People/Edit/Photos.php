<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\PersonPhotos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use TallStackUi\Traits\Interactions;

class Photos extends Component
{
    use Interactions;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public $uploads = [];

    public $backup = [];

    public $photos = null;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        // if needed, create team photo folders
        $path = storage_path('app/public/photos/' . $this->person->team_id);

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $path = storage_path('app/public/photos-096/' . $this->person->team_id);

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $path = storage_path('app/public/photos-384/' . $this->person->team_id);

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $photos_person = Finder::create()->in(public_path('storage/photos/' . $this->person->team_id))->name($this->person->id . '_*.webp');

        $this->photos = collect($photos_person)->map(fn (SplFileInfo $file) => [
            'name'          => $file->getFilename(),
            'name_download' => $this->person->name . ' - ' . $file->getFilename(),
            'extension'     => $file->getExtension(),
            'size'          => Number::fileSize($file->getSize(), 1),
            'path'          => $file->getPath(),
            'url'           => Storage::url('photos-384/' . $this->person->team_id . '/' . $file->getFilename()),
            'url_original'  => Storage::url('photos/' . $this->person->team_id . '/' . $file->getFilename()),
        ])->sortBy('name');
    }

    public function deleteUpload(array $content): void
    {
        /*
        the $content contains:
        [
            'temporary_name',
            'real_name',
            'extension',
            'size',
            'path',
            'url',
        ]
        */

        if (! $this->uploads) {
            return;
        }

        $files = Arr::wrap($this->uploads);

        /** @var UploadedFile $file */
        $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $content['temporary_name'])->first();

        // here we delete the file.
        // even if we have a error here, we simply ignore it because as long as the file is not persisted, it is temporary and will be deleted at some point if there is a failure here
        rescue(fn () => $file->delete(), report: false);

        $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);

        // we guarantee restore of remaining files regardless of upload type, whether you are dealing with multiple or single uploads
        $this->uploads = is_array($this->uploads) ? $collect->toArray() : $collect->first();
    }

    public function updatingUploads(): void
    {
        // we store the uploaded files in the temporary property
        $this->backup = $this->uploads;
    }

    public function updatedUploads(): void
    {
        if (! $this->uploads) {
            return;
        }

        // we merge the newly uploaded files with the saved ones
        $file = Arr::flatten(array_merge($this->backup, [$this->uploads]));

        // we finishing by removing the duplicates
        $this->uploads = collect($file)->unique(fn (UploadedFile $item) => $item->getClientOriginalName())->toArray();
    }

    public function save()
    {
        if ($this->uploads) {
            PersonPhotos::save($this->person, $this->uploads);

            $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', count($this->uploads)))->send();

            return $this->redirect('/people/' . $this->person->id . '/edit-photos');
        }
    }

    public function deletePhoto($photo): void
    {
        Storage::disk('photos')->delete($this->person->team_id . '/' . $photo);
        Storage::disk('photos-096')->delete($this->person->team_id . '/' . $photo);
        Storage::disk('photos-384')->delete($this->person->team_id . '/' . $photo);

        // set new primary
        if ($photo == $this->person->photo) {
            $files = File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp');

            $this->person->update([
                'photo' => $files ? substr($files[0], strrpos($files[0], '/') + 1) : null,
            ]);
        }

        $this->toast()->success(__('app.delete'), __('person.photo_deleted'))->send();

        $this->dispatch('photos_updated');

        $this->mount();
    }

    public function setPrimary($photo): void
    {
        $this->person->update([
            'photo' => $photo,
        ]);

        $this->dispatch('photos_updated');
    }

    // -----------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.edit.photos');
    }
}
