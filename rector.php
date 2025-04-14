<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use RectorLaravel\Set\LaravelSetList;

// use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    // Run Rector on these paths
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/routes',
        __DIR__ . '/database',
    ]);

    // Laravel-specific refactorings
    $rectorConfig->sets([
        LaravelSetList::LARAVEL_120, // Laravel 10 improvements
        LevelSetList::UP_TO_PHP_84,  // Use PHP 8.2 improvements (adjust based on your PHP version)
    ]);
};
