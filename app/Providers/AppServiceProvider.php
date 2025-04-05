<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Setting;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use TallStackUi\Facades\TallStackUi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ------------------------------------------------------------------------------
        // Configure application settings and services
        // ------------------------------------------------------------------------------
        $this->configureUrl();
        $this->configureStrictMode();
        $this->configureLogViewer();
        $this->configureDates();
        $this->configureTallStackUiPersonalization();

        $this->addAboutCommandDetails();

        if (app()->isLocal()) {
            RequestException::dontTruncate();
        }

        // ------------------------------------------------------------------------------
        // This will prevent any destructive commands from being executed
        // in production environments, such as dropping tables or truncating data.
        // This is a safety measure to prevent accidental data loss.
        // Uncomment the line below to enable this feature.
        // ------------------------------------------------------------------------------
        // DB::prohibitDestructiveCommands(app()->isProduction());

        // ------------------------------------------------------------------------------
        // Enable or disable logging based on application settings
        // ------------------------------------------------------------------------------
        if ($this->isDatabaseOnline() && Schema::hasTable('settings')) {
            // Cache the applications settings
            $this->app->singleton('settings', function () {
                return Cache::rememberForever('settings', function () {
                    return Setting::all()->pluck('value', 'key');
                });
            });

            $this->logAllQueries();
            $this->LogAllQueriesSlow();
            $this->logAllQueriesNplusone();
        }
        // ------------------------------------------------------------------------------
    }

    /**
     * Enforce HTTPS (only in production).
     */
    private function configureUrl(): void
    {
        URL::forceHttps(app()->isProduction());
    }

    /**
     * Use Strict Mode (only on local).
     *
     * 1. Prevent Lazy Loading
     * 2. Prevent Silently Discarding Attributes
     * 3. Prevent Accessing Missing Attributes
     * Reference: https://coderflex.com/blog/laravel-strict-mode-all-what-you-need-to-know
     */
    private function configureStrictMode(): void
    {
        Model::shouldBeStrict(app()->isLocal());
    }

    /**
     * Configure LogViewer settings, grant access to developers.
     */
    private function configureLogViewer(): void
    {
        LogViewer::auth(function ($request) {
            return $request->user()->is_developer;
        });
    }

    /**
     * Personalize TallStackUi components.
     *
     * Reference: https://tallstackui.com/docs/personalization/soft
     */
    private function configureTallStackUiPersonalization(): void
    {
        $ui = TallStackUi::personalize();

        $ui->alert()->block('wrapper')->replace('rounded-lg', 'rounded-sm');

        $ui->card()
            ->block('wrapper.first')->replace('gap-4', 'gap-2')
            ->block('wrapper.second')->replace([
                'dark:bg-dark-700' => 'dark:bg-neutral-700',
                'rounded-lg'       => 'rounded-sm',
            ])
            ->block('header.wrapper.base')->replace([
                'dark:border-b-dark-600' => 'dark:border-b-neutral-600',
                'p-4'                    => 'p-2',
            ])
            ->block('footer.wrapper')->replace([
                'dark:border-t-dark-600' => 'dark:border-t-neutral-600',
                'rounded-lg'             => 'rounded-sm',
            ]);

        $ui->carousel()
            ->block('images.base')->append('rounded-sm');

        $ui->dropdown()
            ->block('floating.default')->replace('rounded-lg', 'rounded-sm')
            ->block('action.icon')->replace('text-gray-400', 'text-primary-500 dark:text-primary-300');

        $ui->form('input')
            ->block('input.wrapper')->replace('rounded-md', 'rounded-sm')
            ->block('input.base')->replace('rounded-md', 'rounded-sm')
            ->block('input.color.background')->replace('dark:bg-dark-800', 'dark:bg-dark-950');

        $ui->form('textarea')
            ->block('input.wrapper')->replace('rounded-md', 'rounded-sm')
            ->block('input.base')->replace('rounded-md', 'rounded-sm')
            ->block('input.color.background')->replace('dark:bg-dark-800', 'dark:bg-dark-950');

        $ui->form('label')
            ->block('text')->replace([
                'text-gray-600'      => 'text-gray-700',
                'dark:text-dark-400' => 'dark:text-neutral-500',
            ]);

        $ui->modal()
            ->block('wrapper.first')->replace('bg-gray-400/75', 'bg-gray-400/10')
            ->block('wrapper.fourth')->replace([
                'dark:bg-dark-700' => 'dark:bg-gray-900',
                'rounded-xl'       => 'rounded-sm',
            ]);

        $ui->slide()
            ->block('wrapper.first')->replace('bg-gray-400/75', 'bg-gray-400/10')
            ->block('wrapper.fifth')->replace('dark:bg-dark-700', 'dark:bg-gray-900')
            ->block('body')->replace('dark:text-dark-300', 'dark:text-neutral-300')
            ->block('footer')->append('dark:text-secondary-600');

        $ui->tab()
            ->block('base.wrapper')->replace([
                'dark:bg-dark-700' => 'dark:bg-neutral-700',
                'rounded-lg'       => 'rounded-sm',
            ])
            ->block('item.select')->replace('dark:text-dark-300', 'dark:text-neutral-50');

        $ui->table()
            ->block('wrapper')->replace('rounded-lg', 'rounded-sm')
            ->block('table.td')->replace('py-4', 'py-2');

        $ui->select('styled')
            ->block('input.wrapper.base')->replace([
                'dark:bg-dark-800' => 'dark:bg-dark-950',
                'rounded-md'       => 'rounded-sm',
            ]);
    }

    /**
     * Configure the application's dates.
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Add application details to the About command.
     */
    private function addAboutCommandDetails(): void
    {
        AboutCommand::add('Application', [
            'Name'    => 'Genealogy',
            'Author'  => 'kreaweb.be',
            'GitHub'  => 'https://github.com/MGeurts/genealogy',
            'License' => 'MIT License',
        ]);
    }

    /**
     * Log all queries for debugging purposes.
     */
    private function logAllQueries(): void
    {
        if (settings('log_all_queries')) {
            DB::listen(fn ($query) => Log::debug($query->toRawSQL()));
        }
    }

    /**
     * Log all slow queries for debugging purposes.
     */
    private function LogAllQueriesSlow(): void
    {
        if (settings('log_all_queries_slow')) {
            DB::listen(function ($query): void {
                if ($query->time > (int) settings('log_all_queries_slow_threshold')) {
                    Log::warning('An individual database query exceeded ' . settings('log_all_queries_slow_threshold') . ' ms.', [
                        'sql'  => $query->sql,
                        'raw'  => $query->toRawSQL(),
                        'time' => $query->time,
                    ]);
                }
            });
        }
    }

    /**
     * Log all (N+1) queries for debugging purposes.
     */
    private function logAllQueriesNplusone(): void
    {
        if (settings('log_all_queries_n+1')) {
            Model::handleLazyLoadingViolationUsing(function ($model, $relation): void {
                Log::warning(sprintf(
                    'N+1 Query detected in model %s on relation %s.',
                    get_class($model),
                    $relation
                ));
            });
        }
    }

    /**
     * Check if the database connection is available.
     */
    protected function isDatabaseOnline(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (\Exception $e) {
            // Log the exception if needed for debugging
            // Log::error('Database connection error: ' . $e->getMessage());
            return false;
        }
    }
}
