<?php

namespace App\Http\Middleware;

use App\Models\TeamInvitation;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        //return $request->expectsJson() ? null : route('login');

        // Before we forward to login page, see if this is a valid team invite
        // and use info to show to User.
        if ($request->hasValidSignature() && $request->routeIs('team-invitations.accept')) {
            $invitationId = $request->route('invitation');
            /** @var TeamInvitation $teamInvitation */
            $teamInvitation = TeamInvitation::query()->find($invitationId);
            $teamName = $teamInvitation->team->name ?? null;

            // We should store session value as well, so we can prevent email confirmation
            // since they already responded to an TeamInvitation.
            if ($teamName) {
                $request->session()->put('teamInvitation', $teamName);
            } else {
                /**
                 * If the invitation is deleted (already fulfilled), remove the
                 * intended URL to team invitation route so the User does not get
                 * a 403 after login or register. We can't do this here since
                 * the intended URL is not yet set, but place a marker to do so in.
                 *
                 * @see RedirectIfAuthenticated::handle()
                 */
                $request->session()->put('removeUrlIntended', true);
                $request->session()->flash('status', 'This invitation has expired.');
            }
        }

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
