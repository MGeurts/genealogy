<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

// --------------------------------------------------------------------------------
// schedule daily backup
// --------------------------------------------------------------------------------
Schedule::command('backup:clean')->daily()->at(config('app.backup.daily_cleanup'))
    ->onSuccess(function (): void {
        Log::info('Backup (Scheduled) -- Cleanup succeeded');
    })
    ->onFailure(function (): void {
        Log::warning('Backup (Scheduled) -- Cleanup failed');
    });

Schedule::command('backup:run --only-db')->daily()->at(config('app.backup.daily_run'))
    ->onSuccess(function (): void {
        Log::info('Backup (Scheduled) -- Backup succeeded');
    })
    ->onFailure(function (): void {
        Log::warning('Backup (Scheduled) -- Backup failed');
    });
// --------------------------------------------------------------------------------
