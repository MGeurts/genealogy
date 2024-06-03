<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function create(User $user, array $input): Team
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ])->validateWithBag('createTeam');

        AddingTeam::dispatch($user);

        $user->switchTeam($team = $user->ownedTeams()->create([
            'name'          => $input['name'],
            'description'   => $input['description'] ?? null,
            'personal_team' => false,
        ]));

        // -----------------------------------------------------------------------
        // create team photo folders
        // -----------------------------------------------------------------------
        if (! storage::disk('photos')->exists($team->id)) {
            Storage::disk('photos')->makeDirectory($team->id);
        }

        if (! storage::disk('photos-096')->exists($team->id)) {
            Storage::disk('photos-096')->makeDirectory($team->id);
        }

        if (! storage::disk('photos-384')->exists($team->id)) {
            Storage::disk('photos-384')->makeDirectory($team->id);
        }
        // -----------------------------------------------------------------------

        return $team;
    }
}
