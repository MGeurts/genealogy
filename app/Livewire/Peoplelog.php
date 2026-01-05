<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

final class Peoplelog extends Component
{
    use WithPagination;

    public int $perPage = 25;

    public string $subjectTypeFilter = 'all';

    // -----------------------------------------------------------------------
    public function render(): View
    {
        $timezone = session('timezone', 'UTC');

        $user        = auth()->user();
        $currentTeam = $user?->currentTeam;

        if (! $currentTeam) {
            abort(403, 'No team selected');
        }

        $query = Activity::with('causer')
            ->where('log_name', 'person_couple')
            ->where('team_id', $currentTeam->id)
            ->where('updated_at', '>=', today()->startOfMonth()->subMonths(1));

        // Apply subject_type filter if not 'all'
        if ($this->subjectTypeFilter !== 'all') {
            $query->where('subject_type', $this->subjectTypeFilter);
        }

        $activities = $query
            ->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        // Get distinct subject types for the filter dropdown
        $subjectTypes = Activity::where('log_name', 'person_couple')
            ->where('team_id', $currentTeam->id)
            ->where('updated_at', '>=', today()->startOfMonth()->subMonths(1))
            ->distinct()
            ->pluck('subject_type')
            ->map(fn ($type) => class_basename($type))
            ->sort()
            ->values()
            ->toArray();

        // Transform paginated data using through()
        $activities->through(function ($record) use ($timezone): array {
            $event       = $record->event ?? '';
            $subjectType = $record->subject_type ?? '';
            $description = $record->description ?? '';
            $updatedAt   = $record->updated_at;

            return [
                'event'          => mb_strtoupper($event),
                'subject_type'   => $subjectType !== '' ? class_basename($subjectType) : '',
                'subject_id'     => $record->subject_id,
                'description'    => mb_strtoupper($description),
                'properties_old' => in_array($event, ['updated', 'deleted']) ? ($record->properties['old'] ?? []) : [],
                'properties_new' => in_array($event, ['updated', 'created']) ? ($record->properties['attributes'] ?? []) : [],
                'updated_at'     => $updatedAt?->setTimezone($timezone)->isoFormat('LLL') ?? '',
                'causer'         => $record->causer->name ?? '',
            ];
        });

        return view('livewire.peoplelog', compact('activities', 'subjectTypes'));
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
}
