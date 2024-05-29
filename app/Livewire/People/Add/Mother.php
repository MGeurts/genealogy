<?php

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\MotherForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use App\Tools\PersonPhotos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Mother extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public motherForm $motherForm;

    public $photos = [];

    public $backup = [];

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->motherForm->firstname = null;
        $this->motherForm->surname   = null;
        $this->motherForm->birthname = null;
        $this->motherForm->nickname  = null;

        $this->motherForm->gender_id = null;

        $this->motherForm->yob = null;
        $this->motherForm->dob = null;
        $this->motherForm->pob = null;

        $this->motherForm->photo = null;

        $this->motherForm->person_id = null;
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

        if (! $this->photos) {
            return;
        }

        $files = Arr::wrap($this->photos);

        /** @var UploadedFile $file */
        $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $content['temporary_name'])->first();

        // here we delete the file.
        // even if we have a error here, we simply ignore it because as long as the file is not persisted, it is temporary and will be deleted at some point if there is a failure here
        rescue(fn () => $file->delete(), report: false);

        $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);

        // we guarantee restore of remaining files regardless of upload type, whether you are dealing with multiple or single uploads
        $this->photos = is_array($this->photos) ? $collect->toArray() : $collect->first();
    }

    public function updatingPhotos(): void
    {
        // we store the uploaded files in the temporary property
        $this->backup = $this->photos;
    }

    public function updatedPhotos(): void
    {
        if (! $this->photos) {
            return;
        }

        // we merge the newly uploaded files with the saved ones
        $file = Arr::flatten(array_merge($this->backup, [$this->photos]));

        // we finishing by removing the duplicates
        $this->photos = collect($file)->unique(fn (UploadedFile $item) => $item->getClientOriginalName())->toArray();
    }

    public function saveMother()
    {
        if ($this->isDirty()) {
            $validated = $this->motherForm->validate();

            if (isset($validated['person_id'])) {
                $this->person->update([
                    'mother_id' => $validated['person_id'],
                ]);

                $this->toast()->success(__('app.save'), $this->person->name . ' ' . __('app.saved') . '.')->flash()->send();
            } else {
                $new_person = Person::create([
                    'firstname' => $validated['firstname'],
                    'surname'   => $validated['surname'],
                    'birthname' => $validated['birthname'],
                    'nickname'  => $validated['nickname'],
                    'sex'       => 'f',
                    'gender_id' => $validated['gender_id'] ?? null,
                    'yob'       => $validated['yob'],
                    'dob'       => $validated['dob'],
                    'pob'       => $validated['pob'],
                    'team_id'   => $this->person->team_id,
                ]);

                if ($this->photos) {
                    PersonPhotos::save($new_person, $this->photos);
                }

                $this->person->update([
                    'mother_id' => $new_person->id,
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
        $this->motherForm->firstname != null or
        $this->motherForm->surname != null or
        $this->motherForm->birthname != null or
        $this->motherForm->nickname != null or

        $this->motherForm->gender_id != null or

        $this->motherForm->yob != null or
        $this->motherForm->dob != null or
        $this->motherForm->pob != null or

        $this->motherForm->person_id;
    }

    // -----------------------------------------------------------------------
    public function render()
    {
        $persons = Person::where('id', '!=', $this->person->id)
            ->where('sex', 'f')
            ->OlderThan($this->person->birth_date, $this->person->birth_year)
            ->orderBy('firstname')->orderBy('surname')
            ->get()
            ->map(function ($p) {
                return [
                    'id'   => $p->id,
                    'name' => $p->name . ' [' . strtoupper($p->sex) . '] ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
                ];
            });

        return view('livewire.people.add.mother')->with(compact('persons'));
    }
}
