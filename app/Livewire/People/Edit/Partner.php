<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\PartnerForm;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Partner extends Component
{
    use Interactions;

    // -----------------------------------------------------------------------
    public $person;

    public $couple;

    public PartnerForm $partnerForm;

    public Collection $persons;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->partnerForm->person2_id = ($this->couple->person1_id === $this->person->id) ? $this->couple->person2_id : $this->couple->person1_id;

        $this->partnerForm->date_start = $this->couple->date_start?->format('Y-m-d');
        $this->partnerForm->date_end   = $this->couple->date_end?->format('Y-m-d');

        $this->partnerForm->is_married = $this->couple->is_married;
        $this->partnerForm->has_ended  = $this->couple->has_ended;

        $this->persons = Person::PartnerOffset($this->person->birth_date, $this->person->birth_year)
            ->where('id', '!=', $this->person->id)
            ->orderBy('firstname')->orderBy('surname')
            ->get()
            ->map(function ($p) {
                return [
                    'id'   => $p->id,
                    'name' => $p->name . ' [' . strtoupper($p->sex) . '] (' . $p->birth_formatted . ')',
                ];
            });
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
    public function render(): View
    {
        return view('livewire.people.edit.partner');
    }
}
