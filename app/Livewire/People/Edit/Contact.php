<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\ContactForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Contact extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    public ContactForm $form;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();
    }

    public function saveContact(): void
    {
        $validated = $this->form->validate();

        $this->person->update($validated);

        $this->dispatch('person_updated');

        $this->toast()->success(__('app.save'), __('app.saved'))->send();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.contact');
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->form->street      = $this->person->street;
        $this->form->number      = $this->person->number;
        $this->form->postal_code = $this->person->postal_code;
        $this->form->city        = $this->person->city;
        $this->form->province    = $this->person->province;
        $this->form->state       = $this->person->state;
        $this->form->country     = $this->person->country;
        $this->form->phone       = $this->person->phone;
    }
}
