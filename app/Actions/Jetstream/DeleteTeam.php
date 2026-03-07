<?php

declare(strict_types=1);

namespace App\Actions\Jetstream;

use App\Contracts\PersonPhotoServiceInterface;
use App\Models\Team;
use Laravel\Jetstream\Contracts\DeletesTeams;

final class DeleteTeam implements DeletesTeams
{
    /**
     * Delete the given team.
     */
    public function delete(Team $team): void
    {
        $teamId = (string) $team->id;

        // Delete the photo folders
        /** @var PersonPhotoServiceInterface $photoService */
        $photoService = app(PersonPhotoServiceInterface::class);
        $photoService->deleteTeamDirectory((int) $teamId);

        $user = auth()->user();

        // If the user is currently on this team, switch to another team if available
        if ($user && $user->current_team_id === $team->id) {
            $newTeam = $user->allTeams()->where('id', '!=', $team->id)->first();

            $user->forceFill([
                'current_team_id' => $newTeam?->id,
            ])->save();
        }

        // Permanently delete the team
        $team->purge();
    }
}
