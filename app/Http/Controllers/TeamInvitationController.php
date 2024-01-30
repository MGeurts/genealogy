<?php

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\AddsTeamMembers;

class TeamInvitationController extends Controller
{
    /**
     * Accept a team invitation.
     *
     *
     * @return RedirectResponse
     */
    public function accept(Request $request, TeamInvitation $invitation)
    {
        app(AddsTeamMembers::class)->add(
            $invitation->team->owner,
            $invitation->team,
            $invitation->email,
            $invitation->role
        );

        // Since the user just accepted invite to this team, set that as the current.
        Auth::user()->switchTeam($invitation->team);

        $invitation->delete();

        if ($request->session()->has('teamInvitation')) {
            $request->session()->forget('teamInvitation');
        }

        return redirect(config('fortify.home'))->banner(
            __('auth.invitation_accepted', ['team' => $invitation->team->name]),
        );
    }
}
