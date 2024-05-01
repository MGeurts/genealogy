<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

// --------------------------------------------------------------------------------
// schedule daily backup
// --------------------------------------------------------------------------------
Schedule::command('backup:clean')->daily()->at(config('app.backup_daily_cleanup'))
    ->onSuccess(function () {
        Log::info('Backup (Scheduled) -- Cleanup succeeded');
    })
    ->onFailure(function () {
        Log::warning('Backup (Scheduled) -- Cleanup failed');
    });

Schedule::command('backup:run --only-db')->daily()->at(config('app.backup_daily_run'))
    ->onSuccess(function () {
        Log::info('Backup (Scheduled) -- Backup succeeded');
    })
    ->onFailure(function () {
        Log::warning('Backup (Scheduled) -- Backup failed');
    });
// --------------------------------------------------------------------------------
