<?php

declare(strict_types=1);

return [
    'backup'        => 'Sauvegarde',
    'backups'       => 'Sauvegardes',
    'no_data'       => 'Aucune sauvegarde disponible.',
    'create'        => 'Nouvelle sauvegarde',
    'download'      => 'Télécharger',
    'delete'        => 'Supprimer',
    'delete_backup' => 'cette sauvegarde',
    'id'            => '#',
    'file'          => 'Déposer',
    'size'          => 'Taille',
    'date'          => 'Date',
    'age'           => 'Âge',
    'actions'       => 'Actes',
    'backup_daily'  => 'Les sauvegardes sont créées automatiquement quotidiennement (à ' . config('app.backup.daily_run') . ' heure).',
    'backup_email'  => 'Un e-mail sera envoyé à l\'adresse e-mail de votre application après chaque sauvegarde.',
    'backup_cron_1' => 'Les sauvegardes peuvent être automatisées (exécutées quotidiennement) en émettant la tâche cron suivante sur votre serveur de production :',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan planning:run >> /dev/null 2>&1',
    'created'       => 'La nouvelle sauvegarde a été enregistrée.',
    'deleted'       => 'est supprimé.',
    'downloading'   => 'Le téléchargement est lancé.',
    'failed'        => 'La sauvegarde a échoué.',
    'not_found'     => 'La sauvegarde n\'a pas été trouvée.',
];
