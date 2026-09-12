<?php

declare(strict_types=1);

use App\Livewire\Traits\AuthorizesPersonActions;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use App\Rules\ParentsIdExclusive;
use Illuminate\Support\Collection;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use AuthorizesPersonActions;
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public ?int $father_id = null;

    public ?int $mother_id = null;

    public ?int $parents_id = null;

    /** @var Collection<int, array{id: int, name: string}> */
    public Collection $fathers;

    /** @var Collection<int, array{id: int, name: string}> */
    public Collection $mothers;

    /** @var Collection<int, array{id: int, couple: string}> */
    public Collection $parents;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();

        $persons = Person::where('id', '!=', $this->person->id)
            ->olderThan($this->person->dob, $this->person->yob)
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

        $this->parents = Couple::with(['person1', 'person2'])
            ->olderThan($this->person->birthYear)
            ->get()
            ->sortBy('name')
            ->map(fn ($couple): array => [
                'id'     => $couple->id,
                'couple' => $couple->name . ($couple->date_start ? ' (' . $couple->date_start_formatted . ')' : ''),
            ])->values();
    }

    public function saveFamily(): void
    {
        $this->authorizePermission('person:update');

        $validated = $this->validate();

        $fatherId  = $this->resolveParentId($validated['father_id'] ?? null, 'm');
        $motherId  = $this->resolveParentId($validated['mother_id'] ?? null, 'f');
        $parentsId = $this->resolveParentsId($validated['parents_id'] ?? null);

        $this->person->update([
            'father_id'  => $fatherId,
            'mother_id'  => $motherId,
            'parents_id' => $parentsId,
        ]);

        $this->toast()->success(__('app.save'), __('app.saved'))->send();

        $this->dispatch('family_updated');
    }

    // -----------------------------------------------------------------------
    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'father_id'  => ['nullable', 'integer'],
            'mother_id'  => ['nullable', 'integer'],
            'parents_id' => [
                'nullable',
                'integer',
                new ParentsIdExclusive($this->father_id, $this->mother_id),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'father_id'  => __('person.father'),
            'mother_id'  => __('person.mother'),
            'parents_id' => __('person.parents'),
        ];
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->father_id  = $this->person->father_id;
        $this->mother_id  = $this->person->mother_id;
        $this->parents_id = $this->person->parents_id;
    }

    private function resolveParentId(?int $parentId, string $sex): ?int
    {
        if ($parentId === null) {
            return null;
        }

        return Person::query()
            ->whereKey($parentId)
            ->where('team_id', $this->person->team_id)
            ->where('sex', $sex)
            ->where('id', '!=', $this->person->id)
            ->olderThan($this->person->dob, $this->person->yob)
            ->firstOrFail()
            ->id;
    }

    private function resolveParentsId(?int $parentsId): ?int
    {
        if ($parentsId === null) {
            return null;
        }

        return Couple::query()
            ->whereKey($parentsId)
            ->where('team_id', $this->person->team_id)
            ->where('person1_id', '!=', $this->person->id)
            ->where('person2_id', '!=', $this->person->id)
            ->olderThan($this->person->birthYear)
            ->whereHas('person1', fn ($query) => $query->where('team_id', $this->person->team_id))
            ->whereHas('person2', fn ($query) => $query->where('team_id', $this->person->team_id))
            ->firstOrFail()
            ->id;
    }
};
