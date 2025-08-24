<?php

declare(strict_types=1);

namespace Database\Seeders;

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
            UserSeeder::class,
            TeamSeeder::class,
            DemoSeeder::class,

            // TreeSeeder::class,
        ]);

        // -----------------------------------------------------------------------
        // if you want to use the application in production, please remove :
        //
        // - the DEMO DATA seeder call above
        //
        // - the database seeder /database/seeders/DemoSeeder.php
        // - the database seeder /database/seeders/TeamSeeder.php
        // - the database seeder /database/seeders/TreeSeeder.php
        // - the database seeder /database/seeders/UserSeeder.php
        //
        // - the folder /public/xml
        // - the CONTENT of folder /storage/app/public/photos
        // - the CONTENT of folder /storage/app/public/profile-photos
        // - the CONTENT of folder /storage/app/backups/genealogy
        // -----------------------------------------------------------------------
    }
}
