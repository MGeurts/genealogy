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
        // delete team photo folders
        // -----------------------------------------------------------------------
        if (storage::disk('photos')->exists($team->id)) {
            Storage::disk('photos')->deleteDirectory($team->id);
        }

        if (storage::disk('photos-096')->exists($team->id)) {
            Storage::disk('photos-096')->deleteDirectory($team->id);
        }

        if (storage::disk('photos-384')->exists($team->id)) {
            Storage::disk('photos-384')->deleteDirectory($team->id);
        }
        // -----------------------------------------------------------------------

        $team->purge();
    }
}
