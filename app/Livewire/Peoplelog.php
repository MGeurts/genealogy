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

    public $perPage = 50;

    // -----------------------------------------------------------------------
    public function render(): View
    {
        $timezone = session('timezone', 'UTC');

        $activities = Activity::with('causer')
            ->where('log_name', 'person_couple')
            ->where('team_id', auth()->user()->currentTeam->id)
            ->where('updated_at', '>=', today()->startOfMonth()->subMonths(1))
            ->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        // Transform only the current page data
        $logs = $activities->getCollection()->map(function ($record) use ($timezone) {
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

        return view('livewire.peoplelog', compact('activities'));
    }

    // Optional: Method to change per page
    public function updatedPerPage()
    {
        $this->resetPage();
    }
}
