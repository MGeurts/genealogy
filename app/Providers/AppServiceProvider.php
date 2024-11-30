<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // Configure application settings and services
        $this->configureUrl();
        $this->configureStrictMode();
        $this->configureLogViewer();
        $this->configureTallStackUiPersonalization();

        $this->addAboutCommandDetails();

        // Cache the applications settings
        $this->app->singleton('settings', function () {
            return Cache::rememberForever('settings', function () {
                return Setting::all()->pluck('value', 'key');
            });
        });

        // enable/disable logging based on application settings
        $this->logAllQueries();
        $this->LogAllQueriesSlow();
        $this->logAllQueriesNplusone();
    }

    /**
     * Enforce HTTPS in production.
     */
    private function configureUrl(): void
    {
        URL::forceHttps(app()->isProduction());
    }

    /**
     * Use Strict Mode (not in production).
     *
     * 1. Prevent Lazy Loading
     * 2. Prevent Silently Discarding Attributes
     * 3. Prevent Accessing Missing Attributes
     * Reference: https://coderflex.com/blog/laravel-strict-mode-all-what-you-need-to-know
     */
    private function configureStrictMode(): void
    {
        Model::shouldBeStrict(! app()->isProduction());
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

        // Alerts
        $ui->alert()->block('wrapper')->replace('rounded-lg', 'rounded');

        // Badges
        $ui->badge()->block('wrapper.class')->replace('px-2', 'px-1');

        // Cards
        $ui->card()
            ->block('wrapper.first')->replace('gap-4', 'gap-2')
            ->block('wrapper.second')->replace('rounded-lg', 'rounded')
            ->block('wrapper.second')->replace('dark:bg-dark-700', 'dark:bg-neutral-700')
            ->block('header.wrapper', 'dark:border-b-neutral-600 flex items-center justify-between border-b border-b-gray-100 p-2')
            ->block('footer.wrapper', 'text-secondary-700 dark:text-dark-300 dark:border-t-neutral-600 rounded rounded-t-none border-t p-2')
            ->block('footer.text', 'flex items-center justify-end gap-2');

        // Dropdowns
        $ui->dropdown()
            ->block('floating')->replace('rounded-lg', 'rounded')
            ->block('width')->replace('w-56', 'w-64')
            ->block('action.icon')->replace('text-gray-400', 'text-primary-500 dark:text-primary-300');

        // Forms
        $ui->form('input')
            ->block('input.wrapper')->replace('rounded-md', 'rounded')
            ->block('input.base')->replace('rounded-md', 'rounded');

        $ui->form('textarea')
            ->block('input.wrapper')->replace('rounded-md', 'rounded')
            ->block('input.base')->replace('rounded-md', 'rounded');

        $ui->form('label')
            ->block('text')->replace('text-gray-600', 'text-gray-700')
            ->block('text')->replace('dark:text-dark-400', 'dark:text-dark-500');

        // Modals
        $ui->modal()
            ->block('wrapper.first')->replace('bg-opacity-50', 'bg-opacity-20')
            ->block('wrapper.fourth')->replace('dark:bg-dark-700', 'dark:bg-dark-900')
            ->block('wrapper.fourth')->replace('rounded-xl', 'rounded');

        // Slides
        $ui->slide()
            ->block('wrapper.first')->replace('bg-opacity-50', 'bg-opacity-20')
            ->block('wrapper.fifth')->replace('dark:bg-dark-700', 'dark:bg-dark-900')
            ->block('footer')->append('dark:text-secondary-600');

        // Tabs
        $ui->tab()
            ->block('base.wrapper')->replace('rounded-lg', 'rounded')
            ->block('base.wrapper')->replace('dark:bg-dark-700', 'dark:bg-neutral-700')
            ->block('item.select')->replace('dark:text-dark-300', 'dark:text-neutral-50');

        // Tables
        $ui->table()
            ->block('wrapper')->replace('rounded-lg', 'rounded')
            ->block('table.td')->replace('py-4', 'py-2');
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
     * Log all slow queries (threshold: 250ms) for debugging purposes.
     */
    private function LogAllQueriesSlow(): void
    {
        if (settings('log_all_queries_slow')) {
            DB::listen(function ($query) {
                if ($query->time > 250) {
                    Log::warning('An individual database query exceeded 250 ms.', [
                        'sql' => $query->sql,
                        'raw' => $query->toRawSQL(),
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
            Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
                Log::warning(sprintf(
                    'N+1 Query detected in model %s on relation %s.',
                    get_class($model),
                    $relation
                ));
            });
        }
    }
}
