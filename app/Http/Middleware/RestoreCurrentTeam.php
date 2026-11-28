<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restores a missing team context before views use it, including for existing accounts
 * affected by team deletion. Only a team the user belongs to is selected.
 */
class RestoreCurrentTeam
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->current_team_id === null) {
            $team = $user->personalTeam() ?? $user->allTeams()->first();

            if ($team) {
                $user->switchTeam($team);
            }
        }

        return $next($request);
    }
}
