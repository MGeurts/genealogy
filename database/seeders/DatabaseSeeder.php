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
        $this->call([
            CountrySeeder::class,
            GenderSeeder::class,
        ]);

        // DEMO DATA
        $this->call([
            UserAndTeamSeeder::class,
            DemoSeeder::class,
        ]);

        // -----------------------------------------------------------------------
        // if you want to use the application in production, please remove :
        //
        // - the DEMO DATA seeder call above
        // - the database seeder /database/seeders/DemoSeeder.php
        // - the database seeder /database/seeders/TreeSeeder.php
        // - the database seeder /database/seeders/UserAndTeamSeeder.php
        //
        // - the folder /public/xml
        // - the content of folder /storage/app/public/photos
        // - the content of folder /storage/app/public/profile-photos
        // - the content of folder /storage/app/backups/genealogy
        // -----------------------------------------------------------------------
    }
}
