<?php

namespace App\Livewire\Userlogs;

use App\Models\Userlog;
use Livewire\Component;

class Origin extends Component
{
    public function render()
    {
        $statistics = Userlog::select('country_name')
            ->selectRaw('COUNT(*) AS visitors')
            ->groupBy('country_name')
            ->orderBy('visitors', 'desc')->orderBy('country_name')
            ->get();

        $chart_data = json_encode([
            'labels' => $statistics->pluck('country_name'),
            'data'   => $statistics->pluck('visitors'),
        ]);

        return view('livewire.userlogs.origin')->with([
            'chart_data' => $chart_data,
        ]);
    }
}
