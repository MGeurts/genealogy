<?php

return [
    // Labels
    'backup'  => 'Backup',
    'backups' => 'Backups',
    'no_data' => 'No backups available.',

    // Actions
    'create'        => 'New backup',
    'download'      => 'Download',
    'delete'        => 'Delete',
    'delete_backup' => 'this backup',

    // Attributes
    'id'      => '#',
    'file'    => 'File',
    'size'    => 'Size',
    'date'    => 'Date',
    'age'     => 'Age',
    'actions' => 'Actions',

    // Comments
    'backup_daily'  => 'Backups are created automatically daily (at ' . config('app.backup_daily_run') . ' hour).',
    'backup_email'  => 'An e-mail will be send to your applications e-mail address after each backup.',
    'backup_cron_1' => 'Backups can be automated (run daily) by issuing the following cron job on your production server :',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan schedule:run >> /dev/null 2>&1',

    // Messages
    'created'     => 'The new backup was saved.',
    'deleted'     => 'is deleted.',
    'downloading' => 'The download is started.',
    'failed'      => 'The backup failed.',
    'not_found'   => 'The backup was not found.',
];
