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
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function ($p) {
                return [
                    'event'      => strtoupper($p->event),
                    'created_at' => Carbon::parse($p->created_at)->inUserTimezone()->format('Y-m-d h:i:s'),
                    'causer'     => $p->causer ? implode(' ', array_filter([$p->causer->firstname, $p->causer->surname])) : null,
                    'old'        => $p->properties->get('old'),
                    'new'        => $p->properties->get('attributes'),
                ];
            });
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.history');
    }
}
