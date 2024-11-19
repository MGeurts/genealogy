<?php

declare(strict_types=1);

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
        $teamId = (string) $team->id;

        // Delete the photo folders
        foreach (config('app.photo_folders') as $folder) {
            if (Storage::disk($folder)->exists($teamId)) {
                Storage::disk($folder)->deleteDirectory($teamId);
            }
        }

        // Permanently delete the team
        $team->purge();
    }
}
