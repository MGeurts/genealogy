<?php

declare(strict_types=1);

namespace App\Livewire\Developer;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PeopleLog extends Component
{
    public $logs = [];

    // -----------------------------------------------------------------------
    public function mount()
    {
        $this->logs = collect(DB::select('
            SELECT `activity_log`.`id`, `activity_log`.`event`, `activity_log`.`subject_type`, `activity_log`.`subject_id`, `activity_log`.`causer_id`, `activity_log`.`properties` , `activity_log`.`created_at`, `users`.`firstname`, `users`.`surname`
            FROM activity_log LEFT JOIN users ON (`activity_log`.`causer_id` = `users`.`id`)
            ORDER BY created_at DESC 
            LIMIT 30;
        '));
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.developer.peoplelog');
    }
}
