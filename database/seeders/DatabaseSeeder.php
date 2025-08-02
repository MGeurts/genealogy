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

        if (app()->isProduction()) {
            return;
        }

        // DEMO DATA
        $this->call([
            UserAndTeamSeeder::class,
            DemoSeeder::class,

            // TreeSeeder::class,
        ]);

        // -----------------------------------------------------------------------
        // if you want to use the application in production, please remove :
        //
        // - the folder /public/xml
        // - the content of folder /storage/app/public/photos
        // - the content of folder /storage/app/public/photos-096
        // - the content of folder /storage/app/public/photos-384
        // - the content of folder /storage/app/public/profile-photos
        // - the content of folder /storage/app/backups/genealogy
        // -----------------------------------------------------------------------
    }
}
