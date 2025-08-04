<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\PersonService;
use Illuminate\Support\Facades\Facade;

/**
 * @see PersonService
 */
class Person extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PersonService::class;
    }
}
