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
        // delete team photo folder
        // -----------------------------------------------------------------------
        Storage::disk('photos')->deleteDirectory($team->id);
        
        // -----------------------------------------------------------------------
        // delete team avatars folder
        // -----------------------------------------------------------------------
        Storage::disk('avatars')->deleteDirectory($team->id);
        // -----------------------------------------------------------------------

        $team->purge();
    }
}
