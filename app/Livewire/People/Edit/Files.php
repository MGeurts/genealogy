<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\View;
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

    // -----------------------------------------------------------------------
    public ?string $source = null;

    public ?string $source_date = null;

    public array $uploads = [];

    public array $backup = [];

    public Collection $files;

    // ------------------------------------------------------------------------------
    protected $listeners = [
        'files_updated' => 'mount',
    ];

    // ------------------------------------------------------------------------------
    public function mount(): void
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
            $file = $this->person->addMedia($upload)->toMediaCollection('files', 'files');

            if (isset($this->source)) {
                $file->setCustomProperty('source', $this->source);
            }

            if (isset($this->source_date)) {
                $file->setCustomProperty('source_date', $this->source_date);
            }

            $file->save();
        }

        $this->toast()->success(__('app.save'), trans_choice('person.files_saved', count($this->uploads)))->flash()->send();

        return $this->redirect('/people/' . $this->person->id . '/edit-files');
    }

    public function deleteFile(int $id): void
    {
        foreach ($this->files as $file) {
            if ($file->id == $id) {
                $file->delete();
            }
        }

        $this->reorderFiles();

        $this->toast()->success(__('app.delete'), __('person.file_deleted'))->send();

        $this->dispatch('files_updated');
    }

    private function reorderFiles(): void
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

    public function moveFile(int $position, string $direction): void
    {
        if ($direction == 'up') {
            foreach ($this->files as $file) {
                if ($file->order_column == $position - 1) {
                    $file->order_column = $file->order_column + 1;
                } elseif ($file->order_column == $position) {
                    $file->order_column = $file->order_column - 1;
                }

                $file->save();
            }
        } else {
            foreach ($this->files as $file) {
                if ($file->order_column == $position) {
                    $file->order_column = $file->order_column + 1;
                } elseif ($file->order_column == $position + 1) {
                    $file->order_column = $file->order_column - 1;
                }

                $file->save();
            }
        }

        $this->dispatch('files_updated');
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.files');
    }
}
