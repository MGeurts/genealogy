<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use App\Rules\DobValid;
use App\Rules\YobValid;
use App\Services\MediaLibraryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Father extends Component
{
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    public PersonForm $form;

    public Collection $persons;

    public ?string $selectedTab = null;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->persons = Person::where('id', '!=', $this->person->id)
            ->where('sex', 'm')
            ->OlderThan($this->person->birth_year)
            ->orderBy('firstname')->orderBy('surname')
            ->get()
            ->map(fn ($p): array => [
                'id'   => $p->id,
                'name' => $p->name . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
            ]);

        $this->selectedTab = $this->persons->isEmpty() ? __('person.add_new_person_as_father') : __('person.add_existing_person_as_father');
    }

    /**
     * Handle updates to the uploads property.
     */
    public function updatingUploads(): void
    {
        $this->form->backup = $this->form->uploads;
    }

    /**
     * Process uploaded files and remove duplicates.
     */
    public function updatedUploads(): void
    {
        if (empty($this->form->uploads)) {
            return;
        }

        $this->form->uploads = collect(array_merge($this->form->backup, (array) $this->form->uploads))
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->toArray();
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

    public function saveFather(): void
    {
        $validated = $this->validate($this->rules());

        if (isset($validated['form']['person_id'])) {
            $this->person->update([
                'father_id' => $validated['form']['person_id'],
            ]);

            $this->toast()->success(__('app.save'), __('person.existing_person_linked_as_father'))->send();
        } else {
            $newFather = Person::create(array_merge(
                collect($validated['form'])->only(['firstname', 'surname', 'birthname', 'nickname', 'gender_id', 'yob', 'dob', 'pob'])->toArray(),
                [
                    'sex'     => 'm',
                    'team_id' => $this->person->team_id,
                ]
            ));

            if ($savedCount = MediaLibraryService::savePhotosToPerson($newFather, $this->form->uploads)) {
                $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', $savedCount))->send();
            }

            $this->person->update([
                'father_id' => $newFather->id,
            ]);

            $this->toast()->success(__('app.create'), __('person.new_person_linked_as_father'))->flash()->send();
        }

        $this->redirect(route('people.show', $this->person->id));
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.add.father');
    }

    // ------------------------------------------------------------------------------
    protected function rules(): array
    {
        return [
            'form.firstname' => ['nullable', 'string', 'max:255'],
            'form.surname'   => ['nullable', 'string', 'max:255', 'required_without:form.person_id'],
            'form.birthname' => ['nullable', 'string', 'max:255'],
            'form.nickname'  => ['nullable', 'string', 'max:255'],
            'form.gender_id' => ['nullable', 'integer'],
            'form.yob'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y'), new YobValid],
            'form.dob'       => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', new DobValid],
            'form.pob'       => ['nullable', 'string', 'max:255'],
            'form.uploads.*' => [
                'file',
                'mimetypes:' . implode(',', array_keys(config('app.upload_photo_accept'))),
                'max:' . config('app.upload_max_size'),
            ],

            'form.person_id' => ['nullable', 'integer', 'exists:people,id', 'required_without:form.surname'],
        ];
    }

    protected function messages(): array
    {
        return [
            'form.surname.required_without'   => __('validation.surname.required_without'),
            'form.person_id.required_without' => __('validation.person_id.required_without'),

            'form.uploads.*.file'      => __('validation.file', ['attribute' => __('person.photos')]),
            'form.uploads.*.mimetypes' => __('validation.mimetypes', [
                'attribute' => __('person.photos'),
                'values'    => implode(', ', array_values(config('app.upload_photo_accept'))),
            ]),
            'form.uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photos'),
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

            'form.gender_id' => __('person.gender'),
            'form.yob'       => __('person.yob'),
            'form.dob'       => __('person.dob'),
            'form.pob'       => __('person.pob'),
            'form.uploads'   => __('person.photos'),

            'form.person_id' => __('person.person'),
        ];
    }
}
