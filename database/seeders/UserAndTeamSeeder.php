<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Laravel\Jetstream\Jetstream;

class UserAndTeamSeeder extends Seeder
{
    public function run()
    {
        // -----------------------------------------------------------------------------------
        // create developer users
        // -----------------------------------------------------------------------------------
        User::factory([
            'firstname' => '_',
            'surname' => 'Developer',
            'email' => 'developer@genealogy.test',
            'is_developer' => true,
        ])
            ->withPersonalTeam()
            ->create();

        User::factory([
            'firstname' => 'Kreaweb',
            'surname' => 'Developer',
            'email' => 'kreaweb@genealogy.test',
            'is_developer' => true,
            'language' => 'nl',
        ])
            ->withPersonalTeam()
            ->create();

        // -----------------------------------------------------------------------------------
        // create administrator user
        // -----------------------------------------------------------------------------------
        $administrator = User::factory([
            'firstname' => '_',
            'surname' => 'Administrator',
            'email' => 'administrator@genealogy.test',
        ])
            ->withPersonalTeam()
            ->create();

        // -----------------------------------------------------------------------------------
        // create demo team (owned by administrator)
        // -----------------------------------------------------------------------------------
        $team_demo = $this->createTeamBig('administrator@genealogy.test', 'BRITISH ROYALS');

        $administrator->update([
            'current_team_id' => $team_demo->id,
        ]);

        $team_demo->users()->attach(
            Jetstream::findUserByEmailOrFail($administrator->email),
            ['role' => 'administrator']
        );

        // -----------------------------------------------------------------------------------
        // create special users
        // -----------------------------------------------------------------------------------
        if (true) {
            // manager
            $manager = User::factory([
                'firstname' => '_',
                'surname' => 'Manager',
                'email' => 'manager@genealogy.test',
                'current_team_id' => $team_demo->id,
            ])
                ->withPersonalTeam()
                ->create();

            $team_demo->users()->attach(
                Jetstream::findUserByEmailOrFail($manager->email),
                ['role' => 'manager']
            );

            // editor
            $editor = User::factory([
                'firstname' => '_',
                'surname' => 'Editor',
                'email' => 'editor@genealogy.test',
                'current_team_id' => $team_demo->id,
            ])
                ->withPersonalTeam()
                ->create();

            $team_demo->users()->attach(
                Jetstream::findUserByEmailOrFail($editor->email),
                ['role' => 'editor']
            );
        }

        // -----------------------------------------------------------------------------------
        // create normal users (members)
        // -----------------------------------------------------------------------------------
        if (true) {
            for ($i = 1; $i <= 5; $i++) {
                $user = User::factory([
                    'firstname' => '__',
                    'surname' => 'Member ' . $i,
                    'email' => 'member_' . $i . '@genealogy.test',
                    'current_team_id' => $team_demo,
                ])
                    ->withPersonalTeam()
                    ->create();

                $team_demo->users()->attach(
                    Jetstream::findUserByEmailOrFail($user->email),
                    ['role' => 'member']
                );
            }

            for ($i = 6; $i <= 7; $i++) {
                $user = User::factory([
                    'firstname' => '___',
                    'surname' => 'Member ' . $i,
                    'email' => 'member_' . $i . '@genealogy.test',
                ])
                    ->withPersonalTeam()
                    ->create();
            }
        }
    }

    // -----------------------------------------------------------------------------------
    protected function createTeamPersonal(User $user, string $suffix = "'s TEAM"): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => $user->name . ' ' . $suffix,
            'personal_team' => true,
        ]));
    }

    // -----------------------------------------------------------------------------------
    protected function createTeamBig(string $email, string $name): Team
    {
        $user = Jetstream::findUserByEmailOrFail($email);

        $team = Team::forceCreate([
            'user_id' => $user->id,
            'name' => $name,
            'personal_team' => false,
        ]);

        $user->ownedTeams()->save($team);

        return $team;
    }
}
