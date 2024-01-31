<?php

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\ChildForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Gender;
use App\Models\Person;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Child extends Component
{
    use TrimStringsAndConvertEmptyStringsToNull;
    use WireToast;

    // -----------------------------------------------------------------------
    public $person;

    // -----------------------------------------------------------------------
    public ChildForm $childForm;

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    private function genders()
    {
        return Gender::select('id', 'name')->orderBy('name')->get()->toArray();
    }

    public function saveChild()
    {
        if ($this->isDirty()) {
            $validated = $this->childForm->validate();

            if ($validated['person_id']) {
                if ($this->person->sex === 'm') {
                    Person::findOrFail($validated['person_id'])->update([
                        'father_id' => $this->person->id,
                    ]);
                } else {
                    Person::findOrFail($validated['person_id'])->update([
                        'mother_id' => $this->person->id,
                    ]);
                }

                toast()->success(__('app.saved') . '.', __('app.saved'))->pushOnNextPage();
            } else {
                if ($this->person->sex === 'm') {
                    $new_person = Person::create([
                        'firstname' => ! empty($validated['firstname']) ? $validated['firstname'] : null,
                        'surname' => ! empty($validated['surname']) ? $validated['surname'] : null,
                        'sex' => $validated['sex'],
                        'gender_id' => ! empty($validated['gender_id']) ? $validated['gender_id'] : null,
                        'father_id' => $this->person->id,
                        'team_id' => $this->person->team_id,
                    ]);
                } else {
                    $new_person = Person::create([
                        'firstname' => ! empty($validated['firstname']) ? $validated['firstname'] : null,
                        'surname' => ! empty($validated['surname']) ? $validated['surname'] : null,
                        'sex' => $validated['sex'],
                        'gender_id' => ! empty($validated['gender_id']) ? $validated['gender_id'] : null,
                        'mother_id' => $this->person->id,
                        'team_id' => $this->person->team_id,
                    ]);
                }

                toast()->success(__('app.created') . '.', __('app.create'))->pushOnNextPage();
            }

            $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetChild()
    {
        $this->mount();
    }

    public function isDirty()
    {
        return
            $this->childForm->firstname or
            $this->childForm->surname or
            $this->childForm->sex or
            $this->childForm->gender_id or

            $this->childForm->person_id;
    }

    // -----------------------------------------------------------------------
    public function render()
    {
        $genders = $this->genders();

        if ($this->person->sex === 'm') {
            $persons = Person::where('id', '!=', $this->person->id)
                ->whereNull('father_id')
                ->YoungerThan($this->person->birth_date, $this->person->birth_year)
                ->orderBy('firstname')->orderBy('surname')
                ->get()
                ->map(function ($p) {
                    return ['id' => $p->id, 'name' => $p->name . ' [' . strtoupper($p->sex) . '] ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : '')];
                })->toArray();
        } else {
            $persons = Person::where('id', '!=', $this->person->id)
                ->whereNull('mother_id')
                ->YoungerThan($this->person->birth_date, $this->person->birth_year)
                ->orderBy('firstname')->orderBy('surname')
                ->get()
                ->map(function ($p) {
                    return ['id' => $p->id, 'name' => $p->name . ' [' . strtoupper($p->sex) . '] ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : '')];
                })->toArray();
        }

        return view('livewire.people.add.child')->with(compact('genders', 'persons'));
    }
}
