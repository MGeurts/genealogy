<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\FamilyForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Family extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public $person;

    public FamilyForm $familyForm;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->familyForm->father_id  = $this->person->father_id;
        $this->familyForm->mother_id  = $this->person->mother_id;
        $this->familyForm->parents_id = $this->person->parents_id;
    }

    public function saveFamily()
    {
        if ($this->isDirty()) {
            $validated = $this->familyForm->validate();

            $this->person->update($validated);

            $this->toast()->success(__('app.save'), __('app.saved'))->flash()->send();

            return $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetFamily(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
        $this->familyForm->father_id != $this->person->father_id or
        $this->familyForm->mother_id != $this->person->mother_id or
        $this->familyForm->parents_id != $this->person->parents_id;
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        $persons = Person::where('id', '!=', $this->person->id)
            ->OlderThan($this->person->birth_date, $this->person->birth_year)
            ->orderBy('firstname')->orderBy('surname')
            ->get();

        $fathers = $persons->where('sex', 'm')->map(function ($p) {
            return [
                'id'   => $p->id,
                'name' => $p->name . ' (' . $p->birth_formatted . ')',
            ];
        })->values()->toArray();

        $mothers = $persons->where('sex', 'f')->map(function ($p) {
            return [
                'id'   => $p->id,
                'name' => $p->name . ' (' . $p->birth_formatted . ')',
            ];
        })->values()->toArray();

        $parents = Couple::with(['person_1', 'person_2'])
            ->OlderThan($this->person->birth_date)
            ->get()
            ->sortBy('name')
            ->map(function ($couple) {
                return [
                    'id'     => $couple->id,
                    'couple' => $couple->name . (($couple->date_start) ? ' (' . $couple->date_start_formatted . ')' : ''),
                ];
            })->values();

        return view('livewire.people.edit.family', compact('fathers', 'mothers', 'parents'));
    }
}
