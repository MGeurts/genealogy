<?php

declare(strict_types=1);

return [
    // Labels
    'backup'  => 'Cadangan',
    'backups' => 'Cadangan',
    'no_data' => 'Tidak ada cadangan tersedia.',

    // Actions
    'create'        => 'Cadangan Baru',
    'download'      => 'Unduh',
    'delete'        => 'Hapus',
    'delete_backup' => 'cadangan ini',

    // Attributes
    'id'      => '#',
    'file'    => 'File',
    'size'    => 'Ukuran',
    'date'    => 'Tanggal',
    'age'     => 'Usia',
    'actions' => 'Aksi',

    // Comments
    'backup_daily'  => 'Cadangan dibuat secara otomatis setiap hari (pada pukul ' . config('app.backup.daily_run') . ').',
    'backup_email'  => 'Email akan dikirim ke alamat email aplikasi Anda setelah setiap pencadangan.',
    'backup_cron_1' => 'Pencadangan dapat diotomatiskan (dijalankan setiap hari) dengan menjalankan tugas cron berikut di server produksi Anda:',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan schedule:run >> /dev/null 2>&1',

    // Messages
    'created'     => 'Cadangan baru telah disimpan.',
    'deleted'     => 'telah dihapus.',
    'downloading' => 'Unduhan dimulai.',
    'failed'      => 'Pencadangan gagal.',
    'not_found'   => 'Cadangan tidak ditemukan.',
];
