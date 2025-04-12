<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use App\Models\Userlog;
use Illuminate\Database\Seeder;
use Laravel\Jetstream\Jetstream;
use Spatie\Activitylog\Facades\CauserResolver;

final class UserAndTeamSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------------------------------------------
        // create developer user
        // -----------------------------------------------------------------------------------
        $developer = User::factory([
            'firstname'    => '_',
            'surname'      => 'Developer',
            'email'        => 'developer@genealogy.test',
            'is_developer' => true,
            'timezone'     => 'Europe/Brussels',
        ])
            ->withPersonalTeam()
            ->create();

        if (app()->isLocal()) {
            $this->createUserlogs($developer);
        }

        CauserResolver::setCauser(User::findOrFail(1));

        // -----------------------------------------------------------------------------------
        // create administrator user
        // -----------------------------------------------------------------------------------
        $administrator = User::factory([
            'firstname' => '_',
            'surname'   => 'Administrator',
            'email'     => 'administrator@genealogy.test',
        ])
            ->withPersonalTeam()
            ->create();

        if (app()->isLocal()) {
            $this->createUserlogs($administrator);
        }

        // -----------------------------------------------------------------------------------
        // create demo teams (owned by administrator)
        // -----------------------------------------------------------------------------------
        $team_british_royals = $this->createTeamBig('administrator@genealogy.test', 'BRITISH ROYALS', 'Part of the British Royal family around Queen Elizabeth II');
        $team_kennedy        = $this->createTeamBig('administrator@genealogy.test', 'KENNEDY', 'Part of the Kennedy family around former US President John Fitzgerald Kennedy');

        $administrator->update([
            'current_team_id' => $team_british_royals->id,
        ]);

        // -----------------------------------------------------------------------------------
        // create other special users
        // -----------------------------------------------------------------------------------
        // manager
        $manager = User::factory([
            'firstname'       => '_',
            'surname'         => 'Manager',
            'email'           => 'manager@genealogy.test',
            'current_team_id' => $team_british_royals->id,
        ])
            ->withPersonalTeam()
            ->create();

        if (app()->isLocal()) {
            $this->createUserlogs($manager);
        }

        $team_british_royals->users()->attach(
            Jetstream::findUserByEmailOrFail($manager->email),
            ['role' => 'manager']
        );

        // editor
        $editor = User::factory([
            'firstname'       => '_',
            'surname'         => 'Editor',
            'email'           => 'editor@genealogy.test',
            'current_team_id' => $team_kennedy->id,
        ])
            ->withPersonalTeam()
            ->create();

        if (app()->isLocal()) {
            $this->createUserlogs($editor);
        }

        $team_kennedy->users()->attach(
            Jetstream::findUserByEmailOrFail($editor->email),
            ['role' => 'editor']
        );

        // -----------------------------------------------------------------------------------
        // create normal users (members)
        // -----------------------------------------------------------------------------------
        if (true) {
            for ($i = 1; $i <= 3; $i++) {
                $user = User::factory([
                    'firstname'       => '__',
                    'surname'         => 'Member ' . $i,
                    'email'           => 'member_' . $i . '@genealogy.test',
                    'current_team_id' => $team_british_royals,
                ])
                    ->withPersonalTeam()
                    ->create();

                if (app()->isLocal()) {
                    $this->createUserlogs($user);
                }

                $team_british_royals->users()->attach(
                    Jetstream::findUserByEmailOrFail($user->email),
                    ['role' => 'member']
                );
            }

            for ($i = 4; $i <= 6; $i++) {
                $user = User::factory([
                    'firstname'       => '__',
                    'surname'         => 'Member ' . $i,
                    'email'           => 'member_' . $i . '@genealogy.test',
                    'current_team_id' => $team_kennedy,
                ])
                    ->withPersonalTeam()
                    ->create();

                if (app()->isLocal()) {
                    $this->createUserlogs($user);
                }

                $team_kennedy->users()->attach(
                    Jetstream::findUserByEmailOrFail($user->email),
                    ['role' => 'member']
                );
            }

            for ($i = 7; $i <= 10; $i++) {
                $user = User::factory([
                    'firstname' => '___',
                    'surname'   => 'Member ' . $i,
                    'email'     => 'member_' . $i . '@genealogy.test',
                ])
                    ->withPersonalTeam()
                    ->create();

                if (app()->isLocal()) {
                    $this->createUserlogs($user);
                }
            }
        }
    }

    // -----------------------------------------------------------------------------------
    protected function createUserlogs(User $user): void
    {
        for ($i = 1; $i <= rand(20, 200); $i++) {
            Userlog::factory([
                'user_id' => $user->id,
            ])->create();
        }
    }

    // -----------------------------------------------------------------------------------
    protected function createTeamPersonal(User $user, string $prefix = 'Team ', ?string $description = null): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id'       => $user->id,
            'name'          => $prefix . $user->name,
            'description'   => $description,
            'personal_team' => true,
        ]));
    }

    // -----------------------------------------------------------------------------------
    protected function createTeamBig(string $email, string $name, ?string $description = null): Team
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
}
