<?php

return [
    // Labels
    'backup' => 'Backup',
    'backups' => 'Backups',
    'no_data' => 'Geen backups beschikbaar.',

    // Actions
    'create' => 'Nieuwe backup',
    'download' => 'Download',
    'delete' => 'Verwijder',
    'delete_backup' => 'deze backup',

    // Attributes
    'id' => '#',
    'file' => 'Bestand',
    'size' => 'Grootte',
    'date' => 'Datum',
    'age' => 'Ouderdom',
    'actions' => 'Acties',

    // Comments
    'backup_daily' => 'Backups worden dagelijks (om ' . env('BACKUP_DAILY_RUN') . ' uur) automatisch gegenereerd.',
    'backup_email' => 'Na elke backup wordt een e-mail verstuurd naar het e-mail adres van uw applicatie.',
    'backup_cron_1' => 'Backups kunnen worden geautomatiseerd (dagelijks gestart) door het installeren van onderstaande cron job op de productie server :',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan schedule:run >> /dev/null 2>&1',

    // Messages
    'created' => 'De nieuwe backup werd bewaard.',
    'deleted' => 'is verwijderd.',
    'downloaded' => 'De download is gestart.',
    'not_found' => 'De backup werd niet gevonden.',
];
