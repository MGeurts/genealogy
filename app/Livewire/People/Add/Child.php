<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\ChildForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use App\PersonPhotos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Child extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public ChildForm $childForm;

    public array $photos = [];

    public array $backup = [];

    public Collection $persons;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->childForm->firstname = null;
        $this->childForm->surname   = null;
        $this->childForm->marriedname = null;
        $this->childForm->nickname  = null;

        $this->childForm->sex       = null;
        $this->childForm->gender_id = null;

        $this->childForm->yob = null;
        $this->childForm->dob = null;
        $this->childForm->pob = null;

        $this->childForm->photo = null;

        $this->childForm->person_id = null;

        if ($this->person->sex === 'm') {
            $this->persons = Person::where('id', '!=', $this->person->id)
                ->whereNull('father_id')
                ->YoungerThan($this->person->birth_year)
                ->orderBy('firstname')->orderBy('surname')
                ->get()
                ->map(function ($p) {
                    return [
                        'id'   => $p->id,
                        'name' => $p->name . ' [' . (($p->sex == 'm') ? __('app.male') : __('app.female')) . '] ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
                    ];
                });
        } else {
            $this->persons = Person::where('id', '!=', $this->person->id)
                ->whereNull('mother_id')
                ->YoungerThan($this->person->birth_year)
                ->orderBy('firstname')->orderBy('surname')
                ->get()
                ->map(function ($p) {
                    return [
                        'id'   => $p->id,
                        'name' => $p->name . ' [' . (($p->sex == 'm') ? __('app.male') : __('app.female')) . '] ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
                    ];
                });
        }
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
                if ($this->person->sex === 'm') {
                    $new_person = Person::create([
                        'firstname' => $validated['firstname'],
                        'surname'   => $validated['surname'],
                        'marriedname' => $validated['marriedname'],
                        'nickname'  => $validated['nickname'],
                        'sex'       => $validated['sex'],
                        'gender_id' => $validated['gender_id'] ?? null,
                        'yob'       => $validated['yob'],
                        'dob'       => $validated['dob'],
                        'pob'       => $validated['pob'],
                        'father_id' => $this->person->id,
                        'team_id'   => $this->person->team_id,
                    ]);
                } else {
                    $new_person = Person::create([
                        'firstname' => $validated['firstname'],
                        'surname'   => $validated['surname'],
                        'marriedname' => $validated['marriedname'],
                        'nickname'  => $validated['nickname'],
                        'sex'       => $validated['sex'],
                        'gender_id' => $validated['gender_id'] ?? null,
                        'yob'       => $validated['yob'],
                        'dob'       => $validated['dob'],
                        'pob'       => $validated['pob'],
                        'mother_id' => $this->person->id,
                        'team_id'   => $this->person->team_id,
                    ]);
                }

                if ($this->photos) {
                    $personPhotos = new PersonPhotos($new_person);
                    $personPhotos->save($this->photos);
                }

                $this->toast()->success(__('app.create'), $new_person->name . ' ' . __('app.created') . '.')->flash()->send();
            }

            $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetChild(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
        $this->childForm->firstname != null or
        $this->childForm->surname != null or
        $this->childForm->marriedname != null or
        $this->childForm->nickname != null or

        $this->childForm->sex != null or
        $this->childForm->gender_id != null or

        $this->childForm->yob != null or
        $this->childForm->dob != null or
        $this->childForm->pob != null or

        $this->childForm->person_id;
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.add.child');
    }
}
