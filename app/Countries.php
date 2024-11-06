<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Collection;

class Countries
{
    public Collection $countries;

    // -----------------------------------------------------------------------
    public function __construct(string $locale = 'en')
    {
        $convert_locales_to_countries = [
            'de'    => 'de',
            'en'    => 'en',
            'es'    => 'es',
            'fr'    => 'fr',
            'nl'    => 'nl',
            'pt'    => 'pt',
            'zh_cn' => 'zh',
        ];

        $country = $convert_locales_to_countries[$locale] ?? 'en';

        $path = base_path('vendor/stefangabos/world_countries/data/countries/');

        if (file_exists($path . $country) && is_dir($path . $country)) {
            $this->countries = collect(require $path . $country . '/countries.php');
        } else {
            $this->countries = collect(require $path . 'en/countries.php');
        }
    }

    // -----------------------------------------------------------------------
    public function get(string $country): string
    {
        return $this->countries->filter(function ($item) use ($country) {
            return $item['alpha2'] === $country;
        })->value('name');
    }

    public function all(): Collection
    {
        return $this->countries->map(function ($item) {
            return [
                'id'   => $item['alpha2'],
                'name' => $item['name'],
            ];
        })->values();
    }
    // -----------------------------------------------------------------------
}
