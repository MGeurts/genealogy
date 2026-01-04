<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\AncestorsQueryInterface;
use App\Contracts\DescendantsQueryInterface;
use App\Queries\MySqlAncestorsQuery;
use App\Queries\MySqlDescendantsQuery;
use App\Queries\PgSqlAncestorsQuery;
use App\Queries\PgSqlDescendantsQuery;
use App\Queries\SQLiteAncestorsQuery;
use App\Queries\SQLiteDescendantsQuery;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

final class QueryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DescendantsQueryInterface::class, function () {
            return match ($this->getDatabaseDriver()) {
                'mysql', 'mariadb' => new MySqlDescendantsQuery,
                'pgsql' => new PgSqlDescendantsQuery,
                'sqlite' => new SQLiteDescendantsQuery,
                default => throw new RuntimeException("Unsupported database driver [{$this->getDatabaseDriver()}] for descendants query."),
            };
        });

        $this->app->singleton(AncestorsQueryInterface::class, function () {
            return match ($this->getDatabaseDriver()) {
                'mysql', 'mariadb' => new MySqlAncestorsQuery,
                'pgsql' => new PgSqlAncestorsQuery,
                'sqlite' => new SQLiteAncestorsQuery,
                default => throw new RuntimeException("Unsupported database driver [{$this->getDatabaseDriver()}] for ancestors query."),
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

    /**
     * Get the configured database driver.
     */
    private function getDatabaseDriver(): string
    {
        return config('database.default');
    }
}
