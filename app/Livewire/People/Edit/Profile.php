<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\ProfileForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Carbon\Carbon;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Profile extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    public ProfileForm $profileForm;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->profileForm->person = $this->person;

        $this->profileForm->firstname = $this->person->firstname;
        $this->profileForm->surname   = $this->person->surname;
        $this->profileForm->birthname = $this->person->birthname;
        $this->profileForm->nickname  = $this->person->nickname;

        $this->profileForm->sex       = $this->person->sex;
        $this->profileForm->gender_id = $this->person->gender_id;

        $this->profileForm->yob = $this->person->yob ?? null;
        $this->profileForm->dob = $this->person->dob ? Carbon::parse($this->person->dob)->format('Y-m-d') : null;
        $this->profileForm->pob = $this->person->pob;
    }

    public function saveProfile()
    {
        if ($this->isDirty()) {
            $validated = $this->profileForm->validate();

            $this->person->update($validated);

            $this->toast()->success(__('app.save'), __('app.saved'))->flash()->send();

            return $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetProfile(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
        $this->profileForm->firstname != $this->person->firstname or
        $this->profileForm->surname != $this->person->surname or
        $this->profileForm->birthname != $this->person->birthname or
        $this->profileForm->nickname != $this->person->nickname or

        $this->profileForm->sex != $this->person->sex or
        $this->profileForm->gender_id != $this->person->gender_id or

        $this->profileForm->yob != $this->person->yob or
        $this->profileForm->dob != ($this->person->dob ? Carbon::parse($this->person->dob)->format('Y-m-d') : null) or
        $this->profileForm->pob != $this->person->pob;
    }
}
