<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

// use Illuminate\Support\Str;

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
        Paginator::useBootstrapFive(); // needed for Log Viewer

        // language select for guest users
        // Language will be overruled by language as defined in each authenticated user profile
        view()->composer('components.set.language', function ($view) {
            $view->with('current_locale', app()->getLocale());
            $view->with('available_locales', config('app.available_locales'));
        });

        // log all queries when in not in production
        // if (! app()->isProduction()) {
        //     DB::listen(function ($query) {
        //         logger(Str::replaceArray('?', $query->bindings, $query->sql));
        //     });
        // }

        // log all N+1 queries
        Model::preventLazyLoading();

        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            Log::warning("N+1 Query detected.\r\n" . sprintf('N+1 Query detected in model %s on relation %s.', get_class($model), $relation));
        });
    }
}
