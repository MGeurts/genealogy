<?php

declare(strict_types=1);

namespace App\Livewire\Developer;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Peoplelog extends Component
{
    public Collection $logs;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->logs = collect(DB::select('
            SELECT `activity_log`.`event`, `activity_log`.`subject_type`, `activity_log`.`subject_id`, `activity_log`.`properties` , `activity_log`.`created_at`, `users`.`firstname`, `users`.`surname`
            FROM activity_log LEFT JOIN users ON (`activity_log`.`causer_id` = `users`.`id`)
            ORDER BY activity_log.created_at DESC, activity_log.id DESC 
            LIMIT 30;
        '))->map(function ($record) {
            $properties = collect(json_decode($record->properties));

            return [
                'event'          => strtoupper($record->event),
                'subject_type'   => substr($record->subject_type, strrpos($record->subject_type, '\\') + 1),
                'subject_id'     => $record->subject_id,
                'properties_old' => ($record->event == 'updated' or $record->event == 'deleted') ? $properties['old'] : [],
                'properties_new' => ($record->event == 'updated' or $record->event == 'created') ? $properties['attributes'] : [],
                'created_at'     => date('Y-m-d H:i', strtotime($record->created_at)),
                'causer'         => implode(' ', array_filter([$record->firstname, $record->surname])),
            ];
        });
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.developer.peoplelog');
    }
}
