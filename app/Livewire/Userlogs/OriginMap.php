<?php

declare(strict_types=1);

namespace App\Livewire\Userlogs;

use App\Models\Userlog;
use Illuminate\View\View;
use Khill\Lavacharts\Lavacharts;
use Livewire\Component;

class OriginMap extends Component
{
    // ------------------------------------------------------------------------------
    public function render(): View
    {
        $countries = Userlog::select('country_code')
            ->selectRaw('MIN(country_name) AS country_name')
            ->selectRaw('COUNT(*) AS visitors')
            ->groupBy('country_code')
            ->get();

        $data = $countries->map(function ($country) {
            return [
                [
                    $country->country_code, // v:
                    $country->country_name, // f:
                ],
                $country->visitors,
            ];
        })->toArray();

        $lava = new Lavacharts([
            // This is a fake Google API key, replace it with your own legal Google API key !!
            'maps_api_key' => '23ay5t987354inr28m9crg893crgt9arc98tr2a896tarc2896ta28',
            // The key is only needed for markers, not used in this example !!
        ]);

        $visitors = $lava->DataTable()
            ->addStringColumn('Country')
            ->addNumberColumn('Visitors')
            ->addRows($data);

        $lava->GeoChart('Visitors', $visitors, [
            'colorAxis'                 => ['minValue' => 0,  'colors' => ['#BCD2E8', '#1E3F66']],   // ColorAxis Options
            'datalessRegionColor'       => '#d0d0d0',
            'displayMode'               => 'auto',
            'enableRegionInteractivity' => true,
            'keepAspectRatio'           => true,
            'region'                    => 'world',
            'magnifyingGlass'           => ['enable' => true, 'zoomFactor' => 7.5],                 // MagnifyingGlass Options
            'markerOpacity'             => 1.0,
            'resolution'                => 'countries',
            'sizeAxis'                  => null,
            'backgroundColor'           => '#ffffff',
            'geochartVersion'           => 11,
            'regioncoderVersion'        => 1,
        ]);

        return view('livewire.userlogs.origin-map', compact('lava'));
    }
}
