<?php

declare(strict_types=1);

return [
    // Labels
    'backup'  => 'Yedek',
    'backups' => 'Yedekler',
    'no_data' => 'Hiç yedek bulunamadı.',

    // Actions
    'create'        => 'Yeni yedek',
    'download'      => 'İndir',
    'delete'        => 'Sil',
    'delete_backup' => 'bu yedeği',

    // Attributes
    'id'      => '#',
    'file'    => 'Dosya',
    'size'    => 'Boyut',
    'date'    => 'Tarih',
    'age'     => 'Yaş',
    'actions' => 'İşlemler',

    // Comments
    'backup_daily'  => 'Yedekler günlük olarak otomatik oluşturulur (saat ' . config('app.backup.daily_run') . ').',
    'backup_email'  => 'Her yedekten sonra uygulamanızın e-posta adresine bir e-posta gönderilecektir.',
    'backup_cron_1' => 'Yedekler aşağıdaki cron işi ile otomatikleştirilebilir (günlük çalıştırılır):',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan schedule:run >> /dev/null 2>&1',

    // Messages
    'created'     => 'Yeni yedek kaydedildi.',
    'deleted'     => 'silindi.',
    'downloading' => 'İndirme başlatıldı.',
    'failed'      => 'Yedekleme başarısız oldu.',
    'not_found'   => 'Yedek bulunamadı.',
];
