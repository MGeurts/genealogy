<?php

namespace App\Livewire\Userlogs;

use App\Models\Userlog;
use Livewire\Component;

class Log extends Component
{
    public function render()
    {
        $userlogs_by_date = Userlog::query($months = 3)
            ->select('userlogs.country_name', 'userlogs.country_code', 'userlogs.created_at', 'users.surname', 'users.firstname')
            ->leftjoin('users', 'userlogs.user_id', '=', 'users.id')
            ->where('userlogs.created_at', '>=', now()->startOfMonth()->subMonths($months))
            ->orderBy('userlogs.created_at', 'desc')
            ->get()
            ->groupBy('date');

        return view('livewire.userlogs.log')->with([
            'userlogs_by_date' => $userlogs_by_date,
            'months' => $months,
        ]);
    }
}
