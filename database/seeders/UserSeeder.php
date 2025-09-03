<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Userlog;
use Illuminate\Database\Seeder;
use Spatie\Activitylog\Facades\CauserResolver;

final class UserSeeder extends Seeder
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
        ])->withPersonalTeam()->create();

        if (app()->isLocal()) {
            $this->createUserlogs($developer);
        }

        CauserResolver::setCauser($developer);

        // -----------------------------------------------------------------------------------
        // create administrator user
        // -----------------------------------------------------------------------------------
        $administrator = User::factory([
            'firstname' => '_',
            'surname'   => 'Administrator',
            'email'     => 'administrator@genealogy.test',
        ])->withPersonalTeam()->create();

        if (app()->isLocal()) {
            $this->createUserlogs($administrator);
        }

        // -----------------------------------------------------------------------------------
        // create manager user
        // -----------------------------------------------------------------------------------
        $manager = User::factory([
            'firstname' => '_',
            'surname'   => 'Manager',
            'email'     => 'manager@genealogy.test',
        ])->withPersonalTeam()->create();

        if (app()->isLocal()) {
            $this->createUserlogs($manager);
        }

        // -----------------------------------------------------------------------------------
        // create editor user
        // -----------------------------------------------------------------------------------
        $editor = User::factory([
            'firstname' => '_',
            'surname'   => 'Editor',
            'email'     => 'editor@genealogy.test',
        ])->withPersonalTeam()->create();

        if (app()->isLocal()) {
            $this->createUserlogs($editor);
        }

        // -----------------------------------------------------------------------------------
        // create normal users (members)
        // -----------------------------------------------------------------------------------
        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory([
                'firstname' => '__',
                'surname'   => 'Member ' . $i,
                'email'     => 'member_' . $i . '@genealogy.test',
            ])->withPersonalTeam()->create();

            if (app()->isLocal()) {
                $this->createUserlogs($user);
            }
        }

        for ($i = 4; $i <= 6; $i++) {
            $user = User::factory([
                'firstname' => '__',
                'surname'   => 'Member ' . $i,
                'email'     => 'member_' . $i . '@genealogy.test',
            ])->withPersonalTeam()->create();

            if (app()->isLocal()) {
                $this->createUserlogs($user);
            }
        }

        for ($i = 7; $i <= 10; $i++) {
            $user = User::factory([
                'firstname' => '___',
                'surname'   => 'Member ' . $i,
                'email'     => 'member_' . $i . '@genealogy.test',
            ])->withPersonalTeam()->create();

            if (app()->isLocal()) {
                $this->createUserlogs($user);
            }
        }
    }

    // -----------------------------------------------------------------------------------
    protected function createUserlogs(User $user): void
    {
        $count = random_int(10, 100);

        // Use the DST-safe factory
        Userlog::factory()
            ->count($count)
            ->for($user) // sets user_id automatically
            ->create();
    }
}
