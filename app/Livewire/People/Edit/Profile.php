<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\ProfileForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Profile extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    public ProfileForm $form;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();
    }

    public function saveProfile(): void
    {
        $validated = $this->form->validate();

        $this->person->update($validated);

        $this->toast()->success(__('app.save'), __('app.saved'))->flash()->send();

        $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.profile');
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->form->firstname = $this->person->firstname;
        $this->form->surname   = $this->person->surname;
        $this->form->birthname = $this->person->birthname;
        $this->form->nickname  = $this->person->nickname;
        $this->form->sex       = $this->person->sex;
        $this->form->gender_id = $this->person->gender_id;
        $this->form->yob       = $this->person->yob ?? null;
        $this->form->dob       = $this->person->dob ? Carbon::parse($this->person->dob)->format('Y-m-d') : null;
        $this->form->pob       = $this->person->pob;
        $this->form->summary   = $this->person->summary;
    }
}
