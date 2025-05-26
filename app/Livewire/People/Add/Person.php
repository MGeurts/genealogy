<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\PersonPhotos;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Person extends Component
{
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public PersonForm $form;

    public ?string $selectedTab = null;

    // -----------------------------------------------------------------------
    public function mount(): void {}

    public function updatingUploads(): void
    {
        $this->form->backup = $this->form->uploads;
    }

    public function updatedUploads(): void
    {
        if (empty($this->form->uploads)) {
            return;
        }

        $this->form->uploads = collect(array_merge($this->form->backup, (array) $this->form->uploads))
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->toArray();
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

        if (empty($this->form->uploads)) {
            return;
        }

        $this->form->uploads = collect($this->form->uploads)
            ->filter(fn (UploadedFile $file): bool => $file->getFilename() !== $content['temporary_name'])
            ->values()
            ->toArray();

        rescue(
            fn () => File::delete(storage_path('app/livewire-tmp/' . $content['temporary_name'])),
            report: false
        );
    }

    public function savePerson(): void
    {
        $validated = $this->validate($this->rules());

        $newPerson = \App\Models\Person::create([
            ...$validated['form'],
            'team_id' => Auth()->user()->currentTeam->id,
        ]);

        if ($this->form->uploads) {
            $photos = new PersonPhotos($newPerson);
            $photos->save($this->form->uploads);
        }

        $this->toast()->success(__('app.save'), $newPerson->name . ' ' . __('app.created'))->flash()->send();

        $this->redirect('/people/' . $newPerson->id);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.add.person');
    }

    // ------------------------------------------------------------------------------
    protected function rules(): array
    {
        return [
            'form.firstname' => ['nullable', 'string', 'max:255'],
            'form.surname'   => ['required', 'string', 'max:255'],
            'form.birthname' => ['nullable', 'string', 'max:255'],
            'form.nickname'  => ['nullable', 'string', 'max:255'],
            'form.sex'       => ['required', 'string', 'max:1', 'in:m,f'],
            'form.gender_id' => ['nullable', 'integer'],
            'form.yob'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y'), new YobValid],
            'form.dob'       => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', new DobValid],
            'form.pob'       => ['nullable', 'string', 'max:255'],
            'form.uploads.*' => [
                'file',
                'mimetypes:' . implode(',', array_keys(config('app.upload_photo_accept'))),
                'max:' . config('app.upload_max_size'),
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'form.uploads.*.file'      => __('validation.file', ['attribute' => __('person.photo')]),
            'form.uploads.*.mimetypes' => __('validation.mimetypes', [
                'attribute' => __('person.photo'),
                'values'    => implode(', ', array_values(config('app.upload_photo_accept'))),
            ]),
            'form.uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photo'),
                'max'       => config('app.upload_max_size'),
            ]),
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'form.firstname' => __('person.firstname'),
            'form.surname'   => __('person.surname'),
            'form.birthname' => __('person.birthname'),
            'form.nickname'  => __('person.nickname'),
            'form.sex'       => __('person.sex'),
            'form.gender_id' => __('person.gender'),
            'form.yob'       => __('person.yob'),
            'form.dob'       => __('person.dob'),
            'form.pob'       => __('person.pob'),
            'form.uploads'   => __('person.photos'),
        ];
    }
}
