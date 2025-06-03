<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Collection;

final class Countries
{
    // Mapping of locales to country directory names
    private const array LOCALE_TO_COUNTRY = [
        'de'    => 'de',
        'en'    => 'en',
        'es'    => 'es',
        'fr'    => 'fr',
        'hi'    => 'hi',
        'id'    => 'id',
        'nl'    => 'nl',
        'pt'    => 'pt',
        'tr'    => 'tr',
        'zh_cn' => 'zh',
    ];

    public Collection $countries;

    /**
     * Constructor to initialize the countries collection based on locale.
     */
    public function __construct(string $locale = 'en')
    {
        // Determine the country folder from the locale
        $countryCode = self::LOCALE_TO_COUNTRY[$locale] ?? 'en'; // Default to 'en' if locale is not found

        // Set the base path for the countries data
        $path       = base_path('vendor/stefangabos/world_countries/data/countries/');
        $localePath = $path . $countryCode;

        // Load the country data for the specified locale or fallback to English
        $this->countries = $this->loadCountriesData($localePath) ?? $this->loadCountriesData($path . 'en');
    }

    /**
     * Get the country name by its alpha2 code.
     */
    public function getCountryName(string $countryCode): ?string
    {
        return $this->countries->firstWhere('alpha2', $countryCode)['name'] ?? null;
    }

    /**
     * Get a collection of all countries with their alpha2 code and name.
     */
    public function getAllCountries(): Collection
    {
        return $this->countries->map(fn ($item): array => [
            'id'   => $item['alpha2'],
            'name' => $item['name'],
        ])->values();
    }

    /**
     * Get country names formatted for svgMap's countryNames configuration.
     */
    public function getCountryNamesForSvgMap(): Collection
    {
        return $this->countries->mapWithKeys(fn ($item) => [mb_strtoupper((string) $item['alpha2']) => $item['name']]);
    }

    /**
     * Load countries data from the specified path.
     */
    private function loadCountriesData(string $path): ?Collection
    {
        $filePath = $path . '/countries.php';

        if (file_exists($filePath)) {
            return collect(require $filePath);
        }

        return null;
    }
}
