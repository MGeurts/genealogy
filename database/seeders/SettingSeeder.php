<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settingsData = [
            ['key' => 'log_all_queries', 'value' => false],
            ['key' => 'log_all_queries_slow', 'value' => true],
            ['key' => 'log_all_queries_slow_threshold', 'value' => 500],
            ['key' => 'log_all_queries_nplusone', 'value' => true],
        ];

        Setting::insert($settingsData);
    }
}
