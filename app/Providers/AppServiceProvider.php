<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
// use Illuminate\Support\Str;
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
        // -----------------------------------------------------------------------
        // Language select for guest users
        // Language will be overruled by language as defined in each authenticated user profile
        // -----------------------------------------------------------------------
        view()->composer('components.set.language', function ($view) {
            $view->with('current_locale', app()->getLocale());
            $view->with('available_locales', config('app.available_locales'));
        });

        // -----------------------------------------------------------------------
        // Use Strict Mode (not in production)
        // 1. Prevent Lazy Loading
        // 2. Prevent Silently Discarding Attributes
        // 3. Prevent Access Missing Attributes
        // https://coderflex.com/blog/laravel-strict-mode-all-what-you-need-to-know
        // -----------------------------------------------------------------------
        Model::shouldBeStrict(! app()->isProduction());

        // -----------------------------------------------------------------------
        // LOG-VIEWER : log all N+1 queries
        // -----------------------------------------------------------------------
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            Log::warning("N+1 Query detected.\r\n" . sprintf('N+1 Query detected in model %s on relation %s.', get_class($model), $relation));
        });

        // -----------------------------------------------------------------------
        // LOG-VIEWER : grant access (in production) to developer
        // -----------------------------------------------------------------------
        LogViewer::auth(function ($request) {
            return $request->user()->is_developer;
        });

        // -----------------------------------------------------------------------
        // LOG-VIEWER : log all queries (not in production)
        // -----------------------------------------------------------------------
        // if (! app()->isProduction()) {
        //     DB::listen(function ($query) {
        //         logger(Str::replaceArray('?', $query->bindings, $query->sql));
        //     });
        // }

        // -----------------------------------------------------------------------
        // LOG-VIEWER : log all SLOW queries (not in production)
        // -----------------------------------------------------------------------
        // if (! app()->isProduction()) {
        //     DB::listen(function ($query) {
        //         if ($query->time > 500) {
        //             Log::warning("An individual database query exceeded 500 ms.", ['sql' => $query->sql]);
        //         }
        //     });
        // }

        // -----------------------------------------------------------------------
        // LOG-VIEWER : log all requests
        // -----------------------------------------------------------------------
        // This is done by the middleware \app\Http\Middleware\LogAllRequests.php
        // and can be enabled/disable in \bootstrap\app.php

        // -----------------------------------------------------------------------
        // log users (seen_at)
        // -----------------------------------------------------------------------
        // This is done by the event listener \app\Listeners\UserLogin.php

        // -----------------------------------------------------------------------
        // TallStackUI personalization
        // Ref : https://tallstackui.com/docs/personalization/soft
        // -----------------------------------------------------------------------
        TallStackUi::personalize()->badge()
            ->block('wrapper.class')->replace('px-2', 'px-1');

        TallStackUi::personalize()->button()
            ->block('wrapper.class')->replace('gap-x-2', 'gap-x-0')
            ->block('wrapper.class')->append('!rounded');

        TallStackUi::personalize()->card()
            ->block('wrapper.first')->replace('gap-4', 'gap-2')
            ->block('wrapper.second')->replace('rounded-lg', 'rounded')
            ->block('wrapper.second')->replace('dark:bg-dark-700', 'dark:bg-neutral-700')
            ->block('header.wrapper', 'dark:border-b-neutral-600 flex items-center justify-between border-b border-b-gray-100 p-2')
            ->block('footer.wrapper', 'text-secondary-700 dark:text-dark-300 dark:border-t-neutral-600 rounded rounded-t-none border-t p-2')
            ->block('footer.text', 'flex items-center justify-end gap-2');

        TallStackUi::personalize()->dropdown()
            ->block('floating')->replace('rounded-lg', 'rounded')
            ->block('width')->replace('w-56', 'w-64')
            ->block('action.icon')->replace('text-gray-400', 'text-primary-500 dark:text-primar-300');

        TallStackUi::personalize()->form('input')
            ->block('input.wrapper')->replace('rounded-md', 'rounded')
            ->block('input.base')->replace('rounded-md', 'rounded');

        TallStackUi::personalize()->slide()
            ->block('wrapper.first')->replace('bg-opacity-50', 'bg-opacity-20')
            ->block('wrapper.fifth')->replace('dark:bg-dark-700', 'dark:bg-dark-900')
            ->block('footer')->append('dark:text-secondary-600');

        TallStackUi::personalize()->tab()
            ->block('base.wrapper')->replace('rounded-lg', 'rounded')
            ->block('base.wrapper')->replace('dark:bg-dark-700', 'dark:bg-neutral-700')
            ->block('item.select')->replace('dark:text-dark-300', 'dark:text-neutral-50');

        TallStackUi::personalize()->table()
            ->block('wrapper.class')->replace('rounded-lg', 'rounded')
            ->block('table.td')->replace('py-4', 'py-2');

        // -----------------------------------------------------------------------
        // timezone management
        // -----------------------------------------------------------------------
        Carbon::macro('inApplicationTimezone', function () {
            return $this->tz(config('app.timezone_display'));
        });

        Carbon::macro('inUserTimezone', function () {
            return $this->tz(auth()->user()?->timezone ?? config('app.timezone_display'));
        });

        // -----------------------------------------------------------------------
        // about
        // -----------------------------------------------------------------------
        AboutCommand::add('Application', [
            'Name'    => 'Genealogy',
            'author'  => 'kreaweb.be',
            'github'  => 'https://github.com/MGeurts/genealogy',
            'license' => 'MIT License',
        ]);
        // -----------------------------------------------------------------------
    }

    // -----------------------------------------------------------------------
    // enforce https (in production)
    // -----------------------------------------------------------------------
    protected function configureUrl(): void
    {
        app()->isProduction() and URL::forceScheme('https');
    }
}
