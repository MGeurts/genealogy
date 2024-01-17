<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // System
        $this->call([
            CountrySeeder::class,
            GenderSeeder::class,

            UserAndTeamSeeder::class,
        ]);

        // Demo data
        $this->call([
            DemoSeeder::class,
        ]);
    }
}
