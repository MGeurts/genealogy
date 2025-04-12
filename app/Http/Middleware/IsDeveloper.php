<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IsDeveloper
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // -----------------------------------------------------------------------
        // Check if the user has developer privileges
        // -----------------------------------------------------------------------
        if (! $request->user()?->is_developer) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
