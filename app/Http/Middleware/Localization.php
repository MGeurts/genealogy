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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale') and session()->get('locale') != app()->getLocale()) {
            app()->setLocale(session()->get('locale'));
            Carbon::SetLocale(session()->get('locale'));
            Number::useLocale(session()->get('locale'));
        }

        return $next($request);
    }
}
