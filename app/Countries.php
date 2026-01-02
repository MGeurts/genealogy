<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Collection;

final class Countries
{
    // Mapping of locales to country directory names
    private const array LOCALE_TO_COUNTRY = [
        'de' => 'de',
        'en' => 'en',
        'es' => 'es',
        'fr' => 'fr',
        'hi' => 'hi',
        'id' => 'id',
        'nl' => 'nl',
        'pt' => 'pt',
        'tr' => 'tr',
        // 'vi'    => 'vi', // Vietnamese not available in the package
        'zh_cn' => 'zh',
    ];

    /**
     * @var Collection<int, array{alpha2: string, name: string}>
     */
    public Collection $countries;

    /**
     * Constructor to initialize the countries collection based on locale.
     */
    public function __construct(string $locale = 'en')
    {
        // Determine the country folder from the locale
        $basePath    = base_path('vendor/stefangabos/world_countries/data/countries/');
        $countryCode = self::LOCALE_TO_COUNTRY[$locale] ?? 'en'; // Default to 'en' if locale is not found

        // Load the country data for the specified locale or fallback to English
        $this->countries = $this->loadCountriesData("{$basePath}{$countryCode}") ?? $this->loadCountriesData("{$basePath}en");
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
     *
     * @return Collection<int, array{id: string, name: string}>
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
     *
     * @return Collection<string, string>
     */
    public function getCountryNamesForSvgMap(): Collection
    {
        return $this->countries->mapWithKeys(fn ($item) => [
            mb_strtoupper((string) $item['alpha2']) => $item['name'],
        ]);
    }

    /**
     * Load countries data from the specified path.
     *
     * @return Collection<int, array{alpha2: string, name: string}>|null
     */
    private function loadCountriesData(string $path): ?Collection
    {
        $file = "{$path}/countries.php";

        if (! file_exists($file)) {
            return null;
        }

        /** @var array<int, array{alpha2: string, name: string}> $data */
        $data = require $file;

        return collect($data);
    }
}
