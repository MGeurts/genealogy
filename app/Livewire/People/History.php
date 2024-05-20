<?php

namespace App\Livewire\People;

use Illuminate\Support\Carbon;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class History extends Component
{
    public $person;

    public $activities = [];

    // ------------------------------------------------------------------------------
    public function mount()
    {
        $this->activities = Activity::where('subject_type', 'App\Models\Person')->where('subject_id', $this->person->id)
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
    public function render()
    {
        return view('livewire.people.history');
    }
}
