<?php

declare(strict_types=1);

namespace App\Livewire\Userlogs;

use App\Models\Userlog;
use Livewire\Component;

class Log extends Component
{
    public function render()
    {
        $months = 3;

        $userlogs_by_date = Userlog::select('userlogs.country_name', 'userlogs.country_code', 'userlogs.created_at', 'users.surname', 'users.firstname')
            ->leftjoin('users', 'userlogs.user_id', '=', 'users.id')
            ->where('userlogs.created_at', '>=', now()->startOfMonth()->subMonths($months))
            ->orderBy('userlogs.created_at', 'desc')
            ->get()
            ->groupBy('date');

        return view('livewire.userlogs.log', compact('userlogs_by_date', 'months'));
    }
}
