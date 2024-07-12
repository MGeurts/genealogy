<?php

declare(strict_types=1);

namespace App\Livewire\Userlogs;

use App\Models\Userlog;
use Illuminate\View\View;
use Livewire\Component;

class Origin extends Component
{
    // ------------------------------------------------------------------------------
    public string $chart_data;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $statistics = Userlog::select('country_name')
            ->selectRaw('COUNT(*) AS visitors')
            ->groupBy('country_name')
            ->orderBy('visitors', 'desc')->orderBy('country_name')
            ->get();

        $this->chart_data = json_encode([
            'labels' => $statistics->pluck('country_name'),
            'data'   => $statistics->pluck('visitors'),
        ]);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.userlogs.origin');
    }
}
