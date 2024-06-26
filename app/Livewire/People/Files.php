<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use TallStackUi\Traits\Interactions;

class Files extends Component
{
    use Interactions;
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
        foreach ($this->uploads as $upload) {
            $this->person->addMedia($upload)->toMediaCollection('files', 'files');
        }

        $this->toast()->success(__('app.save'), trans_choice('person.files_saved', count($this->uploads)))->flash()->send();

        return $this->redirect('/people/' . $this->person->id . '/files');
    }

    public function deleteFile($id)
    {
        foreach ($this->files as $file) {
            if ($file->id == $id) {
                $file->delete();
            }
        }

        $this->toast()->success(__('app.delete'), __('person.file_deleted'))->send();

        $this->dispatch('files_updated');

        $this->files = $this->person->getMedia('files');

        $this->reorderFiles();
    }

    private function reorderFiles()
    {
        // renumber positions sequentially
        $i = 0;

        foreach ($this->files as $file) {
            $file->order_column = ++$i;
        }

        $ordered = [];

        foreach ($this->files as $file) {
            array_push($ordered, $file->id);
        }

        Media::setNewOrder($ordered);
    }

    public function moveFile($position, $direction)
    {
        if ($direction == 'up') {
            // move up
            foreach ($this->files as $file) {
                if ($file->order_column == $position - 1) {
                    $file->order_column = $file->order_column + 1;
                } elseif ($file->order_column == $position) {
                    $file->order_column = $file->order_column - 1;
                }

                $file->save();
            }
        } else {
            // move down
            foreach ($this->files as $file) {
                if ($file->order_column == $position) {
                    $file->order_column = $file->order_column + 1;
                } elseif ($file->order_column == $position + 1) {
                    $file->order_column = $file->order_column - 1;
                }

                $file->save();
            }
        }

        $this->files = $this->person->getMedia('files');
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.files');
    }
}
