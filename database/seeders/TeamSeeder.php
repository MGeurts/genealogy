<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Laravel\Jetstream\Jetstream;

final class TeamSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------------------------------------------
        // preload users in one query
        // -----------------------------------------------------------------------------------
        $users = User::whereIn('surname', [
            'Administrator',
            'Manager',
            'Editor',
            'Member 1',
            'Member 2',
            'Member 3',
            'Member 4',
            'Member 5',
            'Member 6',
        ])->get()->keyBy('surname');

        // -----------------------------------------------------------------------------------
        // create demo teams (owned by administrator)
        // -----------------------------------------------------------------------------------
        $teamBritishRoyals = $this->createTeam(
            'administrator@genealogy.test',
            'BRITISH ROYALS',
            'Part of the British Royal family around Queen Elizabeth II'
        );

        $teamKennedy = $this->createTeam(
            'administrator@genealogy.test',
            'KENNEDY',
            'Part of the Kennedy family around former US President John Fitzgerald Kennedy'
        );

        // -----------------------------------------------------------------------------------
        // administrator: only set current team
        // -----------------------------------------------------------------------------------
        $users['Administrator']->update([
            'current_team_id' => $teamBritishRoyals->id,
        ]);

        // -----------------------------------------------------------------------------------
        // manager in British Royals
        // -----------------------------------------------------------------------------------
        $this->assignUserToTeam($users['Manager'], $teamBritishRoyals, 'manager');

        // -----------------------------------------------------------------------------------
        // editor in Kennedy
        // -----------------------------------------------------------------------------------
        $this->assignUserToTeam($users['Editor'], $teamKennedy, 'editor');

        // -----------------------------------------------------------------------------------
        // members 1–3 in British Royals
        // -----------------------------------------------------------------------------------
        collect([1, 2, 3])->each(fn ($i) => $this->assignUserToTeam($users['Member ' . $i], $teamBritishRoyals, 'member')
        );

        // -----------------------------------------------------------------------------------
        // members 4–6 in Kennedy
        // -----------------------------------------------------------------------------------
        collect([4, 5, 6])->each(fn ($i) => $this->assignUserToTeam($users['Member ' . $i], $teamKennedy, 'member')
        );
    }

    // -----------------------------------------------------------------------------------
    protected function createTeam(string $email, string $name, ?string $description = null): Team
    {
        $user = Jetstream::findUserByEmailOrFail($email);

        $team = Team::forceCreate([
            'user_id'       => $user->id,
            'name'          => $name,
            'description'   => $description,
            'personal_team' => false,
        ]);

        $user->ownedTeams()->save($team);

        return $team;
    }

    // -----------------------------------------------------------------------------------
    // helper to attach + update current_team_id
    // -----------------------------------------------------------------------------------
    protected function assignUserToTeam(User $user, Team $team, string $role): void
    {
        $team->users()->syncWithoutDetaching([
            Jetstream::findUserByEmailOrFail($user->email)->id => ['role' => $role],
        ]);

        $user->update(['current_team_id' => $team->id]);
    }
}
