<?php

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\FamilyForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Family extends Component
{
    use TrimStringsAndConvertEmptyStringsToNull;
    use WireToast;

    // -----------------------------------------------------------------------
    public $person;

    // -----------------------------------------------------------------------
    public FamilyForm $familyForm;

    // -----------------------------------------------------------------------
    public function mount()
    {
        $this->familyForm->father_id = $this->person->father_id;
        $this->familyForm->mother_id = $this->person->mother_id;
        $this->familyForm->parents_id = $this->person->parents_id;
    }

    public function saveFamily()
    {
        if ($this->isDirty()) {
            $validated = $this->familyForm->validate();

            $this->person->update($validated);

            toast()->success(__('app.saved') . '.', __('app.save'))->pushOnNextPage();
            $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetFamily()
    {
        $this->mount();
    }

    public function isDirty()
    {
        return
            $this->familyForm->father_id != $this->person->father_id or
            $this->familyForm->mother_id != $this->person->mothe_id or
            $this->familyForm->parents_id != $this->person->parents_id;
    }

    public function render()
    {
        // $fathers = Person::where('sex', 'm')
        //     ->where('id', '!=', $this->person->id)
        //     ->OlderThan($this->person->birth_date, $this->person->birth_year)
        //     ->orderBy('firstname')->orderBy('surname')
        //     ->get()
        //     ->map(function ($p) {
        //         return ['id' => $p->id, 'name' => $p->name . ' (' . $p->birth_formatted . ')'];
        //     })->toArray();

        // $mothers = Person::where('sex', 'f')
        //     ->where('id', '!=', $this->person->id)
        //     ->OlderThan($this->person->birth_date, $this->person->birth_year)
        //     ->orderBy('firstname')->orderBy('surname')
        //     ->get()
        //     ->map(function ($p) {
        //         return ['id' => $p->id, 'name' => $p->name . ' (' . $p->birth_formatted . ')'];
        //     })->toArray();

        $persons = Person::where('id', '!=', $this->person->id)
            ->OlderThan($this->person->birth_date, $this->person->birth_year)
            ->orderBy('firstname')->orderBy('surname')
            ->get();

        $males = $persons->where('sex', 'm')->map(function ($p) {
            return ['id' => $p->id, 'name' => $p->name . ' (' . $p->birth_formatted . ')'];
        });

        // ------------------------------------------------------------------
        // To Do : Only needed for sorting, there must be a better way ??
        // ------------------------------------------------------------------
        $fathers = [];
        foreach ($males as $person) {
            array_push($fathers, $person);
        }
        // ------------------------------------------------------------------

        $females = $persons->where('sex', 'f')->map(function ($p) {
            return ['id' => $p->id, 'name' => $p->name . ' (' . $p->birth_formatted . ')'];
        });

        // ------------------------------------------------------------------
        // To Do : Only needed for sorting, there must be a better way ??
        // ------------------------------------------------------------------
        $mothers = [];
        foreach ($females as $person) {
            array_push($mothers, $person);
        }
        // ------------------------------------------------------------------

        $couples = Couple::with(['person_1', 'person_2'])
            ->OlderThan($this->person->birth_date)
            ->get()
            ->sortBy('name')
            ->map(function ($couple) {
                return ['id' => $couple->id, 'couple' => $couple->name . (($couple->date_start) ? ' (' . $couple->date_start_formatted . ')' : '')];
            });

        // ------------------------------------------------------------------
        // To Do : Only needed for sorting, there must be a better way ??
        // ------------------------------------------------------------------
        $parents = [];
        foreach ($couples as $couple) {
            array_push($parents, $couple);
        }
        // ------------------------------------------------------------------

        return view('livewire.people.edit.family')->with(compact('fathers', 'mothers', 'parents'));
    }
}
