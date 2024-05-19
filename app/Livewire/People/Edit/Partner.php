<?php

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\PartnerForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Partner extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public $person;

    public $couple;

    public PartnerForm $partnerForm;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->partnerForm->person2_id = ($this->couple->person1_id === $this->person->id) ? $this->couple->person2_id : $this->couple->person1_id;

        $this->partnerForm->date_start = $this->couple->date_start?->format('Y-m-d');
        $this->partnerForm->date_end   = $this->couple->date_end?->format('Y-m-d');

        $this->partnerForm->is_married = $this->couple->is_married;
        $this->partnerForm->has_ended  = $this->couple->has_ended;
    }

    public function savePartner()
    {
        if ($this->isDirty()) {
            $validated = $this->partnerForm->validate();

            $this->couple->update([
                'person1_id' => $this->person->id,
                'person2_id' => $validated['person2_id'],
                'date_start' => $validated['date_start'] ?? null,
                'date_end'   => $validated['date_end'] ?? null,
                'is_married' => $validated['is_married'],
                'has_ended'  => ($validated['date_end'] or $validated['has_ended']) ? true : false,
            ]);

            $this->toast()->success(__('app.save'), __('app.saved'))->flash()->send();

            return $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetPartner(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
            $this->partnerForm->person2_id != $this->couple->person2_id or

            $this->partnerForm->date_start != $this->couple->date_start or
            $this->partnerForm->date_end != $this->couple->date_end or

            $this->partnerForm->is_married != $this->couple->is_married or
            $this->partnerForm->has_ended != $this->couple->has_ended;
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        $couple = Couple::findOrFail($this->couple->id)->with(['person_1', 'person_2']);

        $persons = Person::orderBy('firstname', 'asc')->orderBy('surname', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'id'   => $p->id,
                    'name' => $p->name . ' [' . strtoupper($p->sex) . '] (' . $p->birth_formatted . ')',
                ];
            });

        return view('livewire.people.edit.partner')->with(compact('couple', 'persons'));
    }
}
