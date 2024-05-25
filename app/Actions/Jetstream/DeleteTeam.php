<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Contracts\DeletesTeams;

class DeleteTeam implements DeletesTeams
{
    /**
     * Delete the given team.
     */
    public function delete(Team $team): void
    {
        // -----------------------------------------------------------------------
        // delete team photos and avatars folders
        // -----------------------------------------------------------------------
        if (storage::disk('photos')->exists($team->id)) {
            Storage::disk('photos')->deleteDirectory($team->id);
        }

        if (storage::disk('avatars')->exists($team->id)) {
            Storage::disk('avatars')->deleteDirectory($team->id);
        }
        // -----------------------------------------------------------------------

        $team->purge();
    }
}
