<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->withPhpSets(php81: true);
// ->withPhpVersion(PhpVersion::UP_TO_PHP_82)
// ->withPreparedSets(
//     // deadCode: true,
//     codeQuality: true,
//     // TypeDeclarations: true,
//     // privatization: true,
//     // earlyReturn: true,
//     // strictBooleans: true,
// );
// ->sets([LevelSetList::UP_TO_PHP_82]);
// ->withRules([
//     ListToArrayDestructRector::class,
// ]);
// uncomment to reach your current PHP version
// ->withPhpSets()
// ->withTypeCoverageLevel(1)
// ->withDeadCodeLevel(0)
// ->withCodeQualityLevel(0);
