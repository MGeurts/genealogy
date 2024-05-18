<?php

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Tools\Photos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Person extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public PersonForm $personForm;

    public $photos = [];

    public $backup = [];

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->personForm->firstname = null;
        $this->personForm->surname   = null;
        $this->personForm->birthname = null;
        $this->personForm->nickname  = null;

        $this->personForm->sex       = null;
        $this->personForm->gender_id = null;

        $this->personForm->yob = null;
        $this->personForm->dob = null;
        $this->personForm->pob = null;

        $this->personForm->photo = null;
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

    public function savePerson()
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

            if ($this->photos) {
                Photos::save($new_person, $this->photos);
            }

            $this->toast()->success(__('app.save'), $new_person->name . ' ' . __('app.created'))->flash()->send();

            return $this->redirect('/people/' . $new_person->id);
        }
    }

    public function resetPerson(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
        $this->personForm->firstname != null or
        $this->personForm->surname != null or
        $this->personForm->birthname != null or
        $this->personForm->nickname != null or

        $this->personForm->sex != null or
        $this->personForm->gender_id != null or

        $this->personForm->yob != null or
        $this->personForm->dob != null or
        $this->personForm->pob != null;
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.add.person');
    }
}
