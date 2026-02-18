<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

$locales = array_values(
    array_diff(
        (require __DIR__ . '/../../config/app.php')['available_locales'],
        ['en']
    )
);

it('has all language counterparts for all English translation keys', function (string $locale): void {
    $enFiles = File::files(lang_path('en'));

    foreach ($enFiles as $file) {
        $filename       = $file->getFilename();
        $enTranslations = require $file->getPathname();
        $localeFile     = lang_path("{$locale}/{$filename}");

        expect(File::exists($localeFile))->toBeTrue("Missing translation file: lang/{$locale}/{$filename}");

        $localeTranslations = require $localeFile;
        $missingKeys        = array_diff(array_keys($enTranslations), array_keys($localeTranslations));

        expect($missingKeys)->toBeEmpty("Missing keys in lang/{$locale}/{$filename}: " . implode(', ', $missingKeys));
    }
})->with($locales);
