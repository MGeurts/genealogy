<?php

declare(strict_types=1);

namespace App\Actions\People;

use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Finds a small, team-scoped set of people suitable for a relationship picker.
 *
 * Keeping this query outside Livewire components prevents every picker from
 * hydrating an entire family tree while preserving the relationship-specific
 * eligibility checks used when a link is saved.
 */
class SearchRelationshipCandidates
{
    private const MAX_RESULTS = 50;

    /**
     * @param  list<int>  $selectedPersonIds
     * @return Collection<int, array{id: int, name: string}>
     */
    public function handle(Person $person, string $relationship, string $search, array $selectedPersonIds): Collection
    {
        $query = $this->baseQuery($person, $relationship)
            ->select(['id', 'firstname', 'surname', 'sex', 'dob', 'yob']);

        $search = mb_trim($search);

        if (mb_strlen($search) < 2) {
            if ($selectedPersonIds === []) {
                return collect();
            }

            $query->whereIn('id', $selectedPersonIds);
        } else {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('firstname', 'like', $search . '%')
                    ->orWhere('surname', 'like', $search . '%');
            });
        }

        return $query
            ->orderBy('surname')
            ->orderBy('firstname')
            ->limit(self::MAX_RESULTS)
            ->get()
            ->map(fn (Person $candidate): array => [
                'id'   => $candidate->id,
                'name' => $this->label($candidate, $relationship),
            ]);
    }

    /** @return Builder<Person> */
    protected function baseQuery(Person $person, string $relationship): Builder
    {
        $query = Person::query()
            ->where('team_id', $person->team_id)
            ->where('id', '!=', $person->id);

        match ($relationship) {
            'father' => $query
                ->where('sex', 'm')
                ->olderThan($person->dob, $person->yob),
            'mother' => $query
                ->where('sex', 'f')
                ->olderThan($person->dob, $person->yob),
            'child' => $query
                ->whereNull($person->sex === 'm' ? 'father_id' : 'mother_id')
                ->youngerThan($person->dob, $person->yob)
                ->olderThan($person->dod, $person->yod),
            'partner' => $query->partnerOffset($person->dob, $person->yob),
        };

        return $query;
    }

    protected function label(Person $candidate, string $relationship): string
    {
        $label = $candidate->name ?? __('person.person');

        if (in_array($relationship, ['child', 'partner'], true)) {
            $label .= ' [' . ($candidate->sex === 'm' ? __('app.male') : __('app.female')) . ']';
        }

        if ($candidate->birth_formatted !== '') {
            $label .= ' (' . $candidate->birth_formatted . ')';
        }

        return $label;
    }
}
