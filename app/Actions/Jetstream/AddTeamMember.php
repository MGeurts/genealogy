<?php

declare(strict_types=1);

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Rules\Role;

final class AddTeamMember implements AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     */
    public function add(User $user, Team $team, string $email, ?string $role = null)
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $email, $role);

        $newTeamMember = Jetstream::findUserByEmailOrFail($email);

        AddingTeamMember::dispatch($team, $newTeamMember);

        $team->users()->attach(
            $newTeamMember, ['role' => $role]
        );

        TeamMemberAdded::dispatch($team, $newTeamMember);

        /* -------------------------------------------------------------------------------------------- */
        // Log activity: Added Team Member
        /* -------------------------------------------------------------------------------------------- */
        defer(function () use ($user, $team, $newTeamMember, $role): void {
            activity()
                ->useLog('user_team')
                ->performedOn($team)
                ->causedBy($user)
                ->event(__('app.event_added'))
                ->withProperties([
                    'email' => $newTeamMember->email,
                    'name'  => $newTeamMember->name,
                    'role'  => $role,
                ])
                ->log(__('team.member') . ' ' . __('app.event_added'));
        });
        /* -------------------------------------------------------------------------------------------- */

        return redirect('/teams/' . $team->id);
    }

    /**
     * Validate the add member operation.
     */
    private function validate(Team $team, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role'  => $role,
        ], $this->rules(), [
            'email.exists' => __('team.user_not_found'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for adding a team member.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    private function rules(): array
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
            'role'  => Jetstream::hasRoles() ? ['required', 'string', new Role] : null,
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     */
    private function ensureUserIsNotAlreadyOnTeam(Team $team, string $email): Closure
    {
        return function ($validator) use ($team, $email): void {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('team.user_already_in_team')
            );
        };
    }
}
