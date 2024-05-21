<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
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
        // create team photo and avatar folders
        // -----------------------------------------------------------------------
        $path = storage_path('app/public/photos/' . $team->id);

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $path = storage_path('app/public/avatars/' . $team->id);

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        // -----------------------------------------------------------------------

        return $team;
    }
}
