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
            DemoSeeder::class,

            // TreeSeeder::class,
        ]);

        // -----------------------------------------------------------------------
        // if you want to use the application in production, please remove :
        //
        // - all 3 DEMO DATA seeder calls above
        // - the database seeder /database/seeders/DemoSeeder.php
        // - the database seeder /database/seeders/TreeSeeder.php
        // - the database seeder /database/seeders/UserAndTeamSeeder.php
        //
        // - the folder /public/xml
        // - the CONTENT of folder /storage/app/public/photos
        // - the CONTENT of folder /storage/app/public/photos-096
        // - the CONTENT of folder /storage/app/public/photos-384
        // - the CONTENT of folder /storage/app/public/profile-photos
        // - the CONTENT of folder /storage/app/backups/genealogy
        // -----------------------------------------------------------------------
    }
}
