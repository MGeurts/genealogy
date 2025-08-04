<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\UserService
 */
class UserService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\UserService::class;
    }
}
