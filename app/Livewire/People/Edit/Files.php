<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Models\Person;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
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
    public Person $person;

    public ?string $source = null;

    public ?string $source_date = null;

    public array $uploads = [];

    public array $backup = [];

    public ?Collection $files = null;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadFiles();
    }

    /**
     * Handle file deletion from uploads.
     */
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
            ->filter(fn (UploadedFile $file): bool => $file->getFilename() !== $content['temporary_name'])
            ->values()
            ->toArray();

        rescue(
            fn () => File::delete(storage_path('app/livewire-tmp/' . $content['temporary_name'])),
            report: false
        );
    }

    /**
     * Handle updates to the uploads property.
     */
    public function updatingUploads(): void
    {
        $this->backup = $this->uploads;
    }

    /**
     * Process uploaded files and remove duplicates.
     */
    public function updatedUploads(): void
    {
        $this->validate();

        if (empty($this->uploads)) {
            return;
        }

        $this->uploads = collect(array_merge($this->backup, (array) $this->uploads))
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->toArray();
    }

    /**
     * Save uploaded files.
     */
    public function save(): void
    {
        $this->validate();

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

        $this->toast()->success(__('app.save'), trans_choice('person.files_saved', count($this->uploads)))->send();

        $this->dispatch('files_updated');

        // Reload the files collection to reflect changes in the UI
        $this->loadFiles();

        // Clear uploads array
        $this->uploads = [];
    }

    /**
     * Delete a file.
     */
    public function deleteFile(int $id): void
    {
        $file = $this->files->firstWhere('id', $id);

        if ($file) {
            $file->delete();

            $this->reorderFiles();

            $this->toast()->success(__('app.delete'), __('person.file_deleted'))->send();

            $this->dispatch('files_updated');

            // Reload the files collection to reflect changes in the UI
            $this->loadFiles();
        }
    }

    /**
     * Move a file up or down the sorted list.
     */
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

        // Reload the files collection to reflect changes in the UI
        $this->loadFiles();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.files');
    }

    // ------------------------------------------------------------------------------
    protected function rules(): array
    {
        return [
            'uploads.*' => [
                'required',
                'file',
                'mimetypes:' . implode(',', array_keys(config('app.upload_file_accept'))),
                'max:' . config('app.upload_max_size'),
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'uploads.*.required'  => __('validation.required', ['attribute' => __('person.file')]),
            'uploads.*.file'      => __('validation.file', ['attribute' => __('person.file')]),
            'uploads.*.mimetypes' => __('validation.mimetypes', [
                'attribute' => __('person.file'),
                'values'    => implode(', ', array_values(config('app.upload_file_accept'))),
            ]),
            'uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.file'),
                'max'       => config('app.upload_max_size'),
            ]),
        ];
    }

    // -----------------------------------------------------------------------
    private function loadFiles(): void
    {
        $this->files = $this->person->getMedia('files');
    }

    /**
     * Reorder the files.
     */
    private function reorderFiles(): void
    {
        if ($this->files) {
            // renumber positions sequentially
            $ordered = $this->files->sortBy('order_column')->pluck('id')->toArray();

            Media::setNewOrder($ordered);
        }
    }
}
