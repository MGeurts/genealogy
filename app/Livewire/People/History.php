<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

final class History extends Component
{
    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    public Collection $activities;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->activities = Activity::with('causer')
            ->where('subject_type', Person::class)->where('subject_id', $this->person->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($record): array => [
                'event'      => mb_strtoupper((string) $record->event),
                'created_at' => Carbon::parse($record->created_at)->timezone(session('timezone') ?? 'UTC')->format('Y-m-d H:i'),
                'causer'     => $record->causer->name ?? 'Unknown',
                'old'        => $record->properties->get('old'),
                'new'        => $record->properties->get('attributes'),
            ]);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.history');
    }
}
