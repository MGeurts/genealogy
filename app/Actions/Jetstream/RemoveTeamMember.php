<?php

declare(strict_types=1);

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Contracts\RemovesTeamMembers;
use Laravel\Jetstream\Events\TeamMemberRemoved;

final class RemoveTeamMember implements RemovesTeamMembers
{
    /**
     * Remove the team member from the given team.
     */
    public function remove(User $user, Team $team, User $teamMember): void
    {
        $role = $teamMember->teamRole($team);

        $this->authorize($user, $team, $teamMember);

        $this->ensureUserDoesNotOwnTeam($teamMember, $team);

        $team->removeUser($teamMember);

        TeamMemberRemoved::dispatch($team, $teamMember);

        /* -------------------------------------------------------------------------------------------- */
        // Log activity: Remove Team Member
        /* -------------------------------------------------------------------------------------------- */
        defer(function () use ($user, $team, $teamMember, $role): void {
            activity()
                ->useLog('user_team')
                ->performedOn($team)
                ->causedBy($user)
                ->event(__('app.event_removed'))
                ->withProperties([
                    'email' => $teamMember->email,
                    'name'  => $teamMember->name,
                    'role'  => $role->name ?? 'N/A',
                ])
                ->log(__('team.member') . ' ' . __('app.event_removed'));
        });
        /* -------------------------------------------------------------------------------------------- */

        // return redirect('/teams/' . $team->id);
    }

    /**
     * Authorize that the user can remove the team member.
     */
    private function authorize(User $user, Team $team, User $teamMember): void
    {
        if (! Gate::forUser($user)->check('removeTeamMember', $team) &&
            $user->id !== $teamMember->id) {
            throw new AuthorizationException;
        }
    }

    /**
     * Ensure that the currently authenticated user does not own the team.
     */
    private function ensureUserDoesNotOwnTeam(User $teamMember, Team $team): void
    {
        if ($teamMember->id === $team->owner->id) {
            throw ValidationException::withMessages([
                'team' => [__('team.user_not_leave')],
            ])->errorBag('removeTeamMember');
        }
    }
}
