<?php

namespace App\Livewire\People;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;

class Files extends Component
{
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public $uploads = [];

    public $backup = [];

    public $files = null;

    // ------------------------------------------------------------------------------
    public function mount()
    {
        $this->files = $this->person->getMedia('files');
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

        if (! $this->photos) {
            return;
        }

        $files = Arr::wrap($this->photos);

        /** @var UploadedFile $file */
        $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $content['temporary_name'])->first();

        // here we delete the file.
        // even if we have a error here, we simply ignore it because as long as the file is not persisted, it is temporary and will be deleted at some point if there is a failure here
        rescue(fn () => $file->delete(), report: false);

        $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);

        // we guarantee restore of remaining files regardless of upload type, whether you are dealing with multiple or single uploads
        $this->uploads = is_array($this->photos) ? $collect->toArray() : $collect->first();
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
        foreach ($this->uploads as $upload) {
            $this->person->addMedia($upload)->toMediaCollection('files', 'files');
        }
    }

    public function deleteFile($id)
    {
        foreach ($this->files as $file) {
            if ($file->id == $id) {
                $file->delete();
            }
        }

        $this->files = $this->person->getMedia('files');
    }

    public function updateFile($id)
    {
        // update file->name
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.files');
    }
}
