<?php

declare(strict_types=1);

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
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
        if (Storage::disk('photos')->exists($teamId)) {
            Storage::disk('photos')->deleteDirectory($teamId);
        }

        $affectedUsers = $team->users()->where('current_team_id', $team->id)->get();

        if ($team->owner?->current_team_id === $team->id) {
            $affectedUsers->push($team->owner);
        }

        $affectedUsers->each(function (User $user) use ($team): void {
            $newTeam = $user->personalTeam();

            if (! $newTeam || $newTeam->id === $team->id) {
                $newTeam = $user->allTeams()->firstWhere('id', '!=', $team->id);
            }

            $user->forceFill(['current_team_id' => $newTeam?->id])->save();
        });

        // Permanently delete the team
        $team->purge();
    }
}
