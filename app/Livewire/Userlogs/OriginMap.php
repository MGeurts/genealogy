<?php

declare(strict_types=1);

namespace App\Livewire\Userlogs;

use App\Models\Userlog;
use Illuminate\View\View;
use Khill\Lavacharts\Lavacharts;
use Livewire\Component;

class OriginMap extends Component
{
    public $countriesData;
    // ------------------------------------------------------------------------------
    public function render(): View
    {
        $this->countriesData = Userlog::select('country_code')
            ->selectRaw('COUNT(*) AS visitors')
            ->groupBy('country_code')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->country_code => [
                        'visitors' => $item->visitors,
                    ]
                ];
            })->toArray();

        return view('livewire.userlogs.origin-map');
    }
}
