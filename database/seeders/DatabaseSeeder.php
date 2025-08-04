<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            GenderSeeder::class,
        ]);

        // DEMO DATA
        $this->call([
            UserAndTeamSeeder::class,
            // TreeSeeder::class,
        ]);
    }
}
