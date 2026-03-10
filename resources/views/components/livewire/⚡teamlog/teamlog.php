<?php

declare(strict_types=1);

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

new class extends Component
{
    use WithPagination;

    public int $perPage = 50;

    // -----------------------------------------------------------------------
    #[Computed]
    public function activities()
    {
        $timezone = session('timezone', 'UTC');

        $user = auth()->user();
        if (! $user || ! $user->currentTeam) {
            abort(403, 'No team access');
        }

        $paginator = Activity::with('causer')
            ->where('log_name', 'user_team')
            ->where('team_id', $user->currentTeam->id)
            ->where('updated_at', '>=', today()->startOfMonth()->subMonths(1))
            ->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        $paginator->through(function ($record) use ($timezone): array {
            $event = $record->event ?? '';

            return [
                'event'          => mb_strtoupper($event),
                'subject_type'   => class_basename($record->subject_type ?? ''),
                'description'    => mb_strtoupper($record->description ?? ''),
                'properties'     => in_array($event, ['invited', 'removed']) ? ($record->properties ?? []) : [],
                'properties_old' => in_array($event, ['updated', 'deleted']) ? ($record->properties['old'] ?? []) : [],
                'properties_new' => in_array($event, ['updated', 'created']) ? ($record->properties['attributes'] ?? []) : [],
                'updated_at'     => $record->updated_at?->setTimezone($timezone)->isoFormat('LLL') ?? '',
                'causer'         => $record->causer->name ?? '',
            ];
        });

        return $paginator;
    }

    // -----------------------------------------------------------------------
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }
};
