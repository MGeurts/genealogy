<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\PersonPhotoServiceInterface;
use App\Services\Photos\CustomPersonPhotoService;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

/**
 * Registers the person photo service implementation based on configuration.
 *
 * This provider mirrors the query service provider pattern so the concrete
 * photo driver can be swapped via configuration without touching callers.
 */
final class PhotoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PersonPhotoServiceInterface::class, function () {
            $driver = config('app.photo_driver', 'custom');

            return match ($driver) {
                'custom'       => new CustomPersonPhotoService(),
                'medialibrary' => throw new RuntimeException('MediaLibrary photo driver not yet implemented.'),
                default        => throw new RuntimeException("Unsupported photo driver [{$driver}]."),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
