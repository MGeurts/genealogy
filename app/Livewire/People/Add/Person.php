<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\PersonPhotos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Person extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public PersonForm $personForm;

    public array $uploads = [];

    public array $backup = [];

    // -----------------------------------------------------------------------
    public function mount(): void {}

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
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->toArray();
    }

    public function savePerson(): void
    {
        if ($this->isDirty()) {
            $validated = $this->personForm->validate();

            $new_person = \App\Models\Person::create([
                'firstname' => $validated['firstname'],
                'surname'   => $validated['surname'],
                'birthname' => $validated['birthname'],
                'nickname'  => $validated['nickname'],
                'sex'       => $validated['sex'],
                'gender_id' => $validated['gender_id'] ?? null,
                'yob'       => $validated['yob'],
                'dob'       => $validated['dob'],
                'pob'       => $validated['pob'],
                'team_id'   => auth()->user()->currentTeam->id,
            ]);

            if ($this->uploads) {
                $personPhotos = new PersonPhotos($new_person);
                $personPhotos->save($this->uploads);
            }

            $this->toast()->success(__('app.save'), $new_person->name . ' ' . __('app.created'))->flash()->send();

            $this->redirect('/people/' . $new_person->id);
        }
    }

    public function isDirty(): bool
    {
        return
        $this->personForm->firstname !== null or
        $this->personForm->surname !== null or
        $this->personForm->birthname !== null or
        $this->personForm->nickname !== null or
        $this->personForm->sex !== null or
        $this->personForm->gender_id !== null or
        $this->personForm->yob !== null or
        $this->personForm->dob !== null or
        $this->personForm->pob !== null or
        $this->personForm->photo !== null;
    }

    public function resetPerson(): void
    {
        $this->personForm->resetFields();
        $this->uploads = [];
        $this->backup  = [];

        $this->resetErrorBag();
        $this->resetValidation();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.add.person');
    }

    // -----------------------------------------------------------------------
    protected function rules(): array
    {
        return [
            'uploads.*' => [
                'file',
                'mimetypes:' . implode(',', array_keys(config('app.upload_photo_accept'))),
                'max:' . config('app.upload_max_size'),
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'uploads.*.file'      => __('validation.file', ['attribute' => __('person.photo')]),
            'uploads.*.mimetypes' => __('validation.mimetypes', [
                'attribute' => __('person.photo'),
                'values'    => implode(', ', array_values(config('app.upload_photo_accept'))),
            ]),
            'uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photo'),
                'max'       => config('app.upload_max_size'),
            ]),
        ];
    }
}
