<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\FatherForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use App\PersonPhotos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Father extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public FatherForm $fatherForm;

    public array $photos = [];

    public array $backup = [];

    public Collection $persons;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->fatherForm->firstname = null;
        $this->fatherForm->surname   = null;
        $this->fatherForm->birthname = null;
        $this->fatherForm->nickname  = null;

        $this->fatherForm->gender_id = null;

        $this->fatherForm->yob = null;
        $this->fatherForm->dob = null;
        $this->fatherForm->pob = null;

        $this->fatherForm->photo = null;

        $this->fatherForm->person_id = null;

        $this->persons = Person::where('id', '!=', $this->person->id)
            ->where('sex', 'm')
            ->OlderThan($this->person->birth_year)
            ->orderBy('firstname')->orderBy('surname')
            ->get()
            ->map(function ($p) {
                return [
                    'id'   => $p->id,
                    'name' => $p->name . ' ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
                ];
            });
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

    public function saveFather()
    {
        if ($this->isDirty()) {
            $validated = $this->fatherForm->validate();

            if (isset($validated['person_id'])) {
                $this->person->update([
                    'father_id' => $validated['person_id'],
                ]);

                $this->toast()->success(__('app.save'), $this->person->name . ' ' . __('app.saved') . '.')->flash()->send();
            } else {
                $new_person = Person::create([
                    'firstname' => $validated['firstname'],
                    'surname'   => $validated['surname'],
                    'birthname' => $validated['birthname'],
                    'nickname'  => $validated['nickname'],
                    'sex'       => 'm',
                    'gender_id' => $validated['gender_id'] ?? null,
                    'yob'       => $validated['yob'],
                    'dob'       => $validated['dob'],
                    'pob'       => $validated['pob'],
                    'team_id'   => $this->person->team_id,
                ]);

                if ($this->photos) {
                    $personPhotos = new PersonPhotos($new_person);
                    $personPhotos->save($this->photos);
                }

                $this->person->update([
                    'father_id' => $new_person->id,
                ]);

                $this->toast()->success(__('app.create'), $new_person->name . ' ' . __('app.created') . '.')->flash()->send();
            }

            return $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetFather(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
        $this->fatherForm->firstname != null or
        $this->fatherForm->surname != null or
        $this->fatherForm->birthname != null or
        $this->fatherForm->nickname != null or

        $this->fatherForm->gender_id != null or

        $this->fatherForm->yob != null or
        $this->fatherForm->dob != null or
        $this->fatherForm->pob != null or

        $this->fatherForm->person_id;
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.add.father');
    }
}
