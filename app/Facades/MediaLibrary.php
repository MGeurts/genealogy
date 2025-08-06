<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\MediaLibraryService;
use Illuminate\Support\Facades\Facade;

/**
 * @see MediaLibraryService
 */
class MediaLibrary extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MediaLibraryService::class;
    }
}
