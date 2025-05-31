<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Family extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public $father_id = null;

    public $mother_id = null;

    public $parents_id = null;

    public Collection $fathers;

    public Collection $mothers;

    public Collection $parents;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();

        $persons = Person::where('id', '!=', $this->person->id)
            ->OlderThan($this->person->birth_year)
            ->orderBy('firstname')->orderBy('surname')
            ->get();

        $this->fathers = $persons->where('sex', 'm')->map(fn ($p): array => [
            'id'   => $p->id,
            'name' => $p->name . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
        ])->values();

        $this->mothers = $persons->where('sex', 'f')->map(fn ($p): array => [
            'id'   => $p->id,
            'name' => $p->name . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
        ])->values();

        $this->parents = Couple::with(['person_1', 'person_2'])
            ->OlderThan($this->person->birth_year)
            ->get()
            ->sortBy('name')
            ->map(fn ($couple): array => [
                'id'     => $couple->id,
                'couple' => $couple->name . ($couple->date_start ? ' (' . $couple->date_start_formatted . ')' : ''),
            ])->values();
    }

    public function saveFamily(): void
    {
        $validated = $this->validate();

        $this->person->update($validated);

        $this->toast()->success(__('app.save'), __('app.saved'))->flash()->send();

        $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.family');
    }

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return $rules = [
            'father_id'  => ['nullable', 'integer'],
            'mother_id'  => ['nullable', 'integer'],
            'parents_id' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [
            'father_id'  => __('person.father'),
            'mother_id'  => __('person.mother'),
            'parents_id' => __('parents.father'),
        ];
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->father_id  = $this->person->father_id;
        $this->mother_id  = $this->person->mother_id;
        $this->parents_id = $this->person->parents_id;
    }
}
