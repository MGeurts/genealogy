<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

final class Teamlog extends Component
{
    public $logs;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->logs = Activity::with('causer')
            ->where('log_name', 'user_team')
            ->where('team_id', Auth()->user()->currentTeam->id)
            ->where('updated_at', '>=', today()->startOfMonth()->subMonths(1))
            ->get()
            ->sortByDesc('updated_at')
            ->map(fn ($record): array => [
                'event'          => mb_strtoupper((string) $record->event),
                'subject_type'   => mb_substr((string) $record->subject_type, mb_strrpos((string) $record->subject_type, '\\') + 1),
                'description'    => mb_strtoupper($record->description),
                'properties'     => ($record->event === 'invited' or $record->event === 'removed') ? $record->properties : [],
                'properties_old' => ($record->event === 'updated' or $record->event === 'deleted') ? $record->properties['old'] : [],
                'properties_new' => ($record->event === 'updated' or $record->event === 'created') ? $record->properties['attributes'] : [],
                'updated_at'     => $record->updated_at->timezone(session('timezone') ?? 'UTC')->isoFormat('LLL'),
                'causer'         => $record->causer ? $record->causer->name : '',
            ]);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.teamlog');
    }
}
