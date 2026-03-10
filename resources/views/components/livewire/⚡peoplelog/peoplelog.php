<?php

declare(strict_types=1);

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

new class extends Component
{
    use WithPagination;

    public int $perPage = 25;

    public string $subjectTypeFilter = 'all';

    // -----------------------------------------------------------------------
    #[Computed]
    public function activities()
    {
        $timezone    = session('timezone', 'UTC');
        $currentTeam = $this->currentTeam();

        $query = Activity::with('causer')
            ->where('log_name', 'person_couple')
            ->where('team_id', $currentTeam->id)
            ->where('updated_at', '>=', today()->startOfMonth()->subMonths(1));

        if ($this->subjectTypeFilter !== 'all') {
            $query->where('subject_type', $this->subjectTypeFilter);
        }

        $paginator = $query
            ->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        $paginator->through(function ($record) use ($timezone): array {
            $event       = $record->event ?? '';
            $subjectType = $record->subject_type ?? '';
            $description = $record->description ?? '';
            $updatedAt   = $record->updated_at;

            return [
                'event'          => mb_strtoupper($event),
                'subject_type'   => $subjectType !== '' ? class_basename($subjectType) : '',
                'subject_id'     => $record->subject_id,
                'description'    => mb_strtoupper($description),
                'properties_old' => in_array($event, ['updated', 'deleted']) ? $this->sortProperties($record->properties['old'] ?? []) : [],
                'properties_new' => in_array($event, ['updated', 'created']) ? $this->sortProperties($record->properties['attributes'] ?? []) : [],
                'updated_at'     => $updatedAt?->setTimezone($timezone)->isoFormat('LLL') ?? '',
                'causer'         => $record->causer->name ?? '',
            ];
        });

        return $paginator;
    }

    #[Computed]
    public function subjectTypes(): array
    {
        $currentTeam = $this->currentTeam();

        return Activity::where('log_name', 'person_couple')
            ->where('team_id', $currentTeam->id)
            ->where('updated_at', '>=', today()->startOfMonth()->subMonths(1))
            ->distinct()
            ->pluck('subject_type')
            ->map(fn ($type) => class_basename($type))
            ->sort()
            ->values()
            ->toArray();
    }

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->currentTeam();
    }

    // Reset pagination when filter changes
    public function updatedSubjectTypeFilter(): void
    {
        $this->resetPage();
    }

    // Reset pagination when per page changes
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    // -----------------------------------------------------------------------
    private function sortProperties(array $properties): array
    {
        $keyOrder = [
            'firstname', 'surname', 'birthname', 'nickname',
            'sex', 'gender.name',
            'father.name', 'mother.name', 'parents.name',
            'dob', 'yob', 'pob',
            'dod', 'yod', 'pod',
            'summary',
            'street', 'number', 'postal_code', 'city', 'province', 'state', 'country',
            'phone', 'photo',
            'team.name',
        ];

        $sorted = [];

        foreach ($keyOrder as $key) {
            if (array_key_exists($key, $properties)) {
                $sorted[$key] = $properties[$key];
            }
        }

        // Append any keys not in the defined order at the end
        foreach ($properties as $key => $value) {
            if (! array_key_exists($key, $sorted)) {
                $sorted[$key] = $value;
            }
        }

        return $sorted;
    }

    private function currentTeam()
    {
        $user        = auth()->user();
        $currentTeam = $user?->currentTeam;

        if (! $currentTeam) {
            abort(403, 'No team selected');
        }

        return $currentTeam;
    }
};
