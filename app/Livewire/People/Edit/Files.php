<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use TallStackUi\Traits\Interactions;

final class Files extends Component
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
        /* the $content contains:
            [
                'temporary_name',
                'real_name',
                'extension',
                'size',
                'path',
                'url',
            ]
        */

        if (empty($this->uploads)) {
            return;
        }

        $this->uploads = collect($this->uploads)
            ->filter(fn (UploadedFile $file) => $file->getFilename() !== $content['temporary_name'])
            ->values()
            ->toArray();

        rescue(
            fn () => UploadedFile::deleteTemporaryFile($content['temporary_name']),
            report: false
        );
    }

    public function updatingUploads(): void
    {
        $this->backup = $this->uploads;
    }

    public function updatedUploads(): void
    {
        if (empty($this->uploads)) {
            return;
        }

        $this->uploads = collect(array_merge($this->backup, (array) $this->uploads))
            ->unique(fn (UploadedFile $file) => $file->getClientOriginalName())
            ->toArray();
    }

    public function save(): void
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

        $this->redirect('/people/' . $this->person->id . '/edit-files');
    }

    public function deleteFile(int $id): void
    {
        $file = $this->files->firstWhere('id', $id);

        if ($file) {
            $file->delete();

            $this->reorderFiles();

            $this->toast()->success(__('app.delete'), __('person.file_deleted'))->send();

            $this->dispatch('files_updated');
        }
    }

    public function moveFile(int $position, string $direction): void
    {
        $targetPosition = $direction === 'up' ? $position - 1 : $position + 1;

        $this->files->transform(function ($file) use ($position, $targetPosition) {
            if ($file->order_column === $position) {
                $file->order_column = $targetPosition;
            } elseif ($file->order_column === $targetPosition) {
                $file->order_column = $position;
            }

            $file->save();

            return $file;
        });

        $this->dispatch('files_updated');
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.files');
    }

    private function reorderFiles(): void
    {
        if ($this->files) {
            // renumber positions sequentially
            $ordered = $this->files->sortBy('order_column')->pluck('id')->toArray();

            Media::setNewOrder($ordered);
        }
    }
}
