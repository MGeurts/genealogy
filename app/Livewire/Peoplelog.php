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

    public $perPage = 25;

    public $subjectTypeFilter = 'all';

    // -----------------------------------------------------------------------
    public function render(): View
    {
        $timezone = session('timezone', 'UTC');

        $query = Activity::with('causer')
            ->where('log_name', 'person_couple')
            ->where('team_id', auth()->user()->currentTeam->id)
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
            ->where('team_id', auth()->user()->currentTeam->id)
            ->where('updated_at', '>=', today()->startOfMonth()->subMonths(1))
            ->distinct()
            ->pluck('subject_type')
            ->map(fn ($type) => class_basename($type))
            ->sort()
            ->values()
            ->toArray();

        // Transform only the current page data
        $logs = $activities->getCollection()->map(function ($record) use ($timezone): array {
            $event = $record->event;

            return [
                'event'          => mb_strtoupper($event),
                'subject_type'   => class_basename($record->subject_type),
                'subject_id'     => $record->subject_id,
                'description'    => mb_strtoupper($record->description),
                'properties_old' => in_array($event, ['updated', 'deleted']) ? ($record->properties['old'] ?? []) : [],
                'properties_new' => in_array($event, ['updated', 'created']) ? ($record->properties['attributes'] ?? []) : [],
                'updated_at'     => $record->updated_at->setTimezone($timezone)->isoFormat('LLL'),
                'causer'         => $record->causer?->name ?? '',
            ];
        });

        // Replace the collection with transformed data
        $activities->setCollection($logs);

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
