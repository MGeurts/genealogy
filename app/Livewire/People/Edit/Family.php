<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\FamilyForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Family extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public $person;

    public FamilyForm $familyForm;

    public Collection $fathers;

    public Collection $mothers;

    public Collection $parents;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->familyForm->father_id  = $this->person->father_id;
        $this->familyForm->mother_id  = $this->person->mother_id;
        $this->familyForm->parents_id = $this->person->parents_id;

        $persons = Person::where('id', '!=', $this->person->id)
            ->OlderThan($this->person->birth_year)
            ->orderBy('firstname')->orderBy('surname')
            ->get();

        $this->fathers = $persons->where('sex', 'm')->map(function ($p) {
            return [
                'id'   => $p->id,
                'name' => $p->name . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
            ];
        })->values();

        $this->mothers = $persons->where('sex', 'f')->map(function ($p) {
            return [
                'id'   => $p->id,
                'name' => $p->name . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
            ];
        })->values();

        $this->parents = Couple::with(['person_1', 'person_2'])
            ->OlderThan($this->person->birth_year)
            ->get()
            ->sortBy('name')
            ->map(function ($couple) {
                return [
                    'id'     => $couple->id,
                    'couple' => $couple->name . ($couple->date_start) ? ' (' . $couple->date_start_formatted . ')' : '',
                ];
            })->values();
    }

    public function saveFamily()
    {
        if ($this->isDirty()) {
            $validated = $this->familyForm->validate();

            $this->person->update($validated);

            $this->toast()->success(__('app.save'), __('app.saved'))->flash()->send();

            $this->redirect('/people/' . $this->person->id);
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
    public function render(): View
    {
        return view('livewire.people.edit.family');
    }
}
