<?php

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\ContactForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Contact extends Component
{
    use TrimStringsAndConvertEmptyStringsToNull;
    use WireToast;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public ContactForm $contactForm;

    // -----------------------------------------------------------------------
    public function mount()
    {
        $this->contactForm->street = $this->person->street;
        $this->contactForm->number = $this->person->number;
        $this->contactForm->postal_code = $this->person->postal_code;
        $this->contactForm->city = $this->person->city;
        $this->contactForm->province = $this->person->province;
        $this->contactForm->state = $this->person->state;
        $this->contactForm->country_id = $this->person->country_id;
        $this->contactForm->phone = $this->person->phone;
    }

    public function saveContact()
    {
        if ($this->isDirty()) {
            $validated = $this->contactForm->validate();

            $this->person->update($validated);

            $this->dispatch('person_updated');
            toast()->success(__('app.saved') . '.', __('app.save'))->push();
        }
    }

    public function resetContact()
    {
        $this->mount();
    }

    public function isDirty()
    {
        return
            $this->contactForm->street != $this->person->street or
            $this->contactForm->number != $this->person->number or
            $this->contactForm->postal_code != $this->person->postal_code or
            $this->contactForm->city != $this->person->city or
            $this->contactForm->province != $this->person->province or
            $this->contactForm->state != $this->person->state or
            $this->contactForm->country_id != $this->person->country_id or
            $this->contactForm->phone != $this->person->phone;
    }
}
