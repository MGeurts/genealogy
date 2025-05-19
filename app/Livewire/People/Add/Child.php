<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\ChildForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use App\PersonPhotos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Child extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public Person $person;

    public ChildForm $childForm;

    public array $uploads = [];

    public array $backup = [];

    public Collection $persons;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->persons = Person::where('id', '!=', $this->person->id)
            ->whereNull($this->person->sex === 'm' ? 'father_id' : 'mother_id')
            ->YoungerThan($this->person->birth_year)
            ->orderBy('firstname')->orderBy('surname')
            ->get()
            ->map(fn ($p): array => [
                'id'   => $p->id,
                'name' => $p->name . ' [' . (($p->sex === 'm') ? __('app.male') : __('app.female')) . '] ' . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
            ]);
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

    public function saveChild(): void
    {
        if ($this->isDirty()) {
            $validated = $this->childForm->validate();

            if (isset($validated['person_id'])) {
                if ($this->person->sex === 'm') {
                    Person::findOrFail($validated['person_id'])->update([
                        'father_id' => $this->person->id,
                    ]);

                    $this->toast()->success(__('app.save'), $this->person->name . ' ' . __('app.saved') . '.')->flash()->send();
                } else {
                    Person::findOrFail($validated['person_id'])->update([
                        'mother_id' => $this->person->id,
                    ]);

                    $this->toast()->success(__('app.save'), $this->person->name . ' ' . __('app.saved') . '.')->flash()->send();
                }
            } else {
                $new_person = Person::create([
                    'firstname'                                            => $validated['firstname'],
                    'surname'                                              => $validated['surname'],
                    'birthname'                                            => $validated['birthname'],
                    'nickname'                                             => $validated['nickname'],
                    'sex'                                                  => $validated['sex'],
                    'gender_id'                                            => $validated['gender_id'] ?? null,
                    'yob'                                                  => $validated['yob'],
                    'dob'                                                  => $validated['dob'],
                    'pob'                                                  => $validated['pob'],
                    $this->person->sex === 'm' ? 'father_id' : 'mother_id' => $this->person->id,
                    'team_id'                                              => $this->person->team_id,
                ]);

                if ($this->uploads) {
                    $personPhotos = new PersonPhotos($new_person);
                    $personPhotos->save($this->uploads);
                }

                $this->toast()->success(__('app.create'), $new_person->name . ' ' . __('app.created') . '.')->flash()->send();
            }

            $this->redirect(route('people.show', $this->person->id));
        }
    }

    public function isDirty(): bool
    {
        return
        $this->childForm->firstname !== null or
        $this->childForm->surname !== null or
        $this->childForm->birthname !== null or
        $this->childForm->nickname !== null or
        $this->childForm->sex !== null or
        $this->childForm->gender_id !== null or
        $this->childForm->yob !== null or
        $this->childForm->dob !== null or
        $this->childForm->pob !== null or

        $this->childForm->person_id;
    }

    public function resetChild(): void
    {
        $this->childForm->resetFields();
        $this->uploads = [];
        $this->backup  = [];

        $this->resetErrorBag();
        $this->resetValidation();
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.add.child');
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
