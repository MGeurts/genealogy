<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        if (true) {
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
    }

    // -----------------------------------------------------------------------------------
    protected function createUserlogs(User $user): void
    {
        $faker = Faker::create();
        $count = random_int(20, 200);

        $logs = [];

        for ($i = 0; $i < $count; $i++) {
            $logs[] = [
                'user_id'      => $user->id,
                'country_name' => $faker->country(),
                'country_code' => $faker->countryCode(),
                'created_at'   => $faker->dateTimeBetween('-2 year', '-1 day'),
            ];
        }

        DB::table('userlogs')->insert($logs);
    }
}
