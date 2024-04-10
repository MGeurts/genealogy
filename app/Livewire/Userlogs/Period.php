<?php

namespace App\Livewire\Userlogs;

use App\Models\Userlog;
use Livewire\Component;

class Period extends Component
{
    public $period;

    public $options;

    public $statistics;

    public $chart_data;

    public function mount()
    {
        $this->period = 'month';

        $this->options = [
            ['value' => 'year', 'label' => __('userlog.year')],
            ['value' => 'month', 'label' => __('userlog.month')],
            ['value' => 'week', 'label' => __('userlog.week')],
            ['value' => 'day', 'label' => __('userlog.day')],
        ];

        $this->updatedPeriod();
    }

    public function updatedPeriod()
    {
        $this->statistics = match ($this->period) {
            'year' => Userlog::selectRaw('YEAR(created_at) AS period')
                ->selectRaw('COUNT(*) AS visitors')
                ->groupBy('period')
                ->orderBy('period')
                ->get(),
            'month' => Userlog::selectRaw('LPAD(MONTH(created_at), 2, 0) AS period')
                ->selectRaw('COUNT(*) AS visitors')
                ->whereYear('created_at', date('Y'))
                ->groupBy('period')
                ->orderBy('period')
                ->get(),
            'week' => Userlog::selectRaw('LPAD(WEEK(created_at, 1), 2, 0) AS period')
                ->selectRaw('COUNT(*) AS visitors')
                ->whereYear('created_at', date('Y'))
                ->groupBy('period')
                ->orderBy('period')
                ->get(),
            'day' => Userlog::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") AS period')
                ->selectRaw('COUNT(*) AS visitors')
                ->whereYear('created_at', date('Y'))
                ->groupBy('period')
                ->orderBy('period')
                ->get()
        };

        $this->chart_data = json_encode([
            'labels' => $this->statistics->pluck('period'),
            'data' => $this->statistics->pluck('visitors'),
        ]);
    }

    public function render()
    {
        return view('livewire.userlogs.period')->with([
            'chart_data' => $this->chart_data,
        ]);
    }
}
