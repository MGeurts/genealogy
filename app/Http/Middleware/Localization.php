<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // -----------------------------------------------------------------------
        // Check and apply the locale from session if it differs from the current app locale
        // -----------------------------------------------------------------------
        $locale = session('locale');

        if ($locale && $locale !== app()->getLocale()) {
            app()->setLocale($locale);
            Carbon::setLocale($locale);
            Number::useLocale($locale);
        }

        return $next($request);
    }
}
