<?php

declare(strict_types=1);

namespace App\Livewire\Userlogs;

use App\Models\Userlog;
use Illuminate\View\View;
use Livewire\Component;

class Period extends Component
{
    // ------------------------------------------------------------------------------
    public string $year;

    public array $years;

    public string $period;

    public array $periods;

    public string $chart_data;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->year = date('Y');

        $this->years = [
            ['value' => date('Y'), 'label' => date('Y')],
            ['value' => date('Y') - 1, 'label' => date('Y') - 1],
            ['value' => date('Y') - 2, 'label' => date('Y') - 2],
        ];

        $this->period = 'month';

        $this->periods = [
            ['value' => 'year', 'label' => __('userlog.year')],
            ['value' => 'month', 'label' => __('userlog.month')],
            ['value' => 'week', 'label' => __('userlog.week')],
            ['value' => 'day', 'label' => __('userlog.day')],
        ];

        $this->prepare();
    }

    public function updatedYear(): void
    {
        $this->prepare();
    }

    public function updatedPeriod(): void
    {
        $this->prepare();
    }

    protected function prepare(): void
    {
        $statistics = match ($this->period) {
            'year' => Userlog::selectRaw('YEAR(created_at) AS period')
                ->selectRaw('COUNT(*) AS visitors')
                ->groupBy('period')
                ->orderBy('period')
                ->get(),
            'month' => Userlog::selectRaw('LPAD(MONTH(created_at), 2, 0) AS period')
                ->selectRaw('COUNT(*) AS visitors')
                ->whereYear('created_at', $this->year)
                ->groupBy('period')
                ->orderBy('period')
                ->get(),
            'week' => Userlog::selectRaw('LPAD(WEEK(created_at, 1), 2, 0) AS period')
                ->selectRaw('COUNT(*) AS visitors')
                ->whereYear('created_at', $this->year)
                ->groupBy('period')
                ->orderBy('period')
                ->get(),
            default => Userlog::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") AS period')
                ->selectRaw('COUNT(*) AS visitors')
                ->whereYear('created_at', $this->year)
                ->groupBy('period')
                ->orderBy('period')
                ->get()
        };

        $this->chart_data = json_encode([
            'labels' => $statistics->pluck('period'),
            'data'   => $statistics->pluck('visitors'),
        ]);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.userlogs.period');
    }
}
