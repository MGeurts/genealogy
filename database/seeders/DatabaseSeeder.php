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
        // 如果要在生产环境中使用应用程序，请删除以下文件
        // - the DEMO DATA seeder call above
        // - the database seeder /database/seeders/DemoSeeder.php
        // - the database seeder /database/seeders/TreeSeeder.php
        // - the database seeder /database/seeders/UserAndTeamSeeder.php
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
