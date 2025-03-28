<?php

declare(strict_types=1);

return [
    // Labels
    'backup'  => 'Sicherung',
    'backups' => 'Sicherungen',
    'no_data' => 'Keine Sicherungen verfügbar.',

    // Actions
    'create'        => 'Neue Sicherung',
    'download'      => 'Download',
    'delete'        => 'Löschen',
    'delete_backup' => 'diese Sicherung',

    // Attributes
    'id'      => '#',
    'file'    => 'Datei',
    'size'    => 'Size',
    'date'    => 'Datum',
    'age'     => 'Alter',
    'actions' => 'Aktionen',

    // Comments
    'backup_daily'  => 'Sicherungen werden täglich automatisch erstellt (um ' . config('app.backup.daily_run') . ' Uhr).',
    'backup_email'  => 'Nach jeder Sicherung wird eine E-Mail an die E-Mail Adresse deiner Anwendung gesendet.',
    'backup_cron_1' => 'Sicherungen können automatisiert (täglich ausgeführt) werden, indem du folgenden Cron-Job auf deinem Produktionsserver ausführst:',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan schedule:run >> /dev/null 2>&1',

    // Messages
    'created'     => 'Neue Sicherung wurde erstellt.',
    'deleted'     => 'wurde gelöscht.',
    'downloading' => 'Der Download hat begonnen.',
    'failed'      => 'Die Sicherung ist fehlgeschlagen.',
    'not_found'   => 'Sicherung konnte nicht gefunden werden.',
];
