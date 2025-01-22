<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class History extends Component
{
    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------
    public Collection $activities;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->activities = Activity::with('causer')
            ->where('subject_type', 'App\Models\Person')->where('subject_id', $this->person->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($record) {
                return [
                    'event'      => strtoupper($record->event),
                    'created_at' => Carbon::parse($record->created_at)->timezone(session('timezone') ?? 'UTC')->format('Y-m-d H:i'),
                    'causer'     => $record->causer ? implode(' ', array_filter([$record->causer->firstname, $record->causer->surname])) : null,
                    'old'        => $record->properties->get('old'),
                    'new'        => $record->properties->get('attributes'),
                ];
            });
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.history');
    }
}
