<?php

declare(strict_types=1);

return [
    // Menus
    'about'            => 'Tentang',
    'dependencies'     => 'Dependensi',
    'help'             => 'Bantuan',
    'home'             => 'Beranda',
    'menu'             => 'Menu',
    'privacy_policy'   => 'Kebijakan Privasi',
    'session'          => 'Sesi',
    'terms_of_service' => 'Ketentuan Layanan',
    'useful_links'     => 'Tautan Berguna',
    'impressum'        => 'Informasi Legal',
    'log_viewer'       => 'Penampil Log',

    // Labels
    'all'               => 'Semua',
    'filter'            => 'Filter',
    'api_tokens'        => 'Token API',
    'attention'         => 'Perhatian',
    'contact'           => 'Kontak',
    'datasheet'         => 'Lembar Data',
    'death'             => 'Kematian',
    'documentation'     => 'Dokumentasi',
    'family_chart'      => 'Bagan Keluarga',
    'female'            => 'Perempuan',
    'history'           => 'Riwayat',
    'male'              => 'Laki-laki',
    'manage_account'    => 'Kelola Akun',
    'my_profile'        => 'Profil Saya',
    'nothing_available' => 'Tidak ada yang tersedia',
    'nothing_found'     => 'Tidak ada yang ditemukan',
    'nothing_recorded'  => 'Belum ada yang tercatat.',
    'search'            => 'Cari',
    'yes'               => 'Ya',
    'no'                => 'Tidak',
    'error'             => 'Kesalahan',

    'created_at' => 'Dibuat pada',
    'updated_at' => 'Diperbarui pada',
    'deleted_at' => 'Dihapus pada',

    'language'        => 'Bahasa',
    'language_select' => 'Pilih Bahasa',
    'language_set'    => 'Bahasa diatur ke',

    'attribute' => 'Atribut',
    'old'       => 'Lama',
    'new'       => 'Baru',
    'value'     => 'Nilai',

    // Actions
    'add'     => 'Tambah',
    'cancel'  => 'Batal',
    'create'  => 'Buat',
    'created' => 'Dibuat',

    'download'    => 'Unduh',
    'downloading' => 'Unduhan dimulai.',

    'move_down' => 'Pindahkan ke Bawah',
    'move_up'   => 'Pindahkan ke Atas',

    'show_death'        => 'Tampilkan Kematian',
    'show_family_chart' => 'Tampilkan Bagan Keluarga',
    'show_profile'      => 'Tampilkan Profil',

    'save'   => 'Simpan',
    'saved'  => 'Disimpan',
    'select' => 'Pilih',
    'show'   => 'Tampilkan',

    // Deletion confirm attributes
    'abort_no'            => 'Tidak, batalkan',
    'are_you_sure'        => 'Apakah Anda yakin?',
    'confirm'             => 'Konfirmasi',
    'delete'              => 'Hapus',
    'deleted'             => 'telah dihapus',
    'delete_yes'          => 'Ya, hapus',
    'delete_question'     => 'Apakah Anda yakin untuk menghapus :model?',
    'delete_person'       => 'orang ini',
    'delete_relationship' => 'hubungan ini',
    'disconnect'          => 'Putuskan hubungan',
    'disconnected'        => 'telah diputuskan hubungannya',
    'disconnect_child'    => 'anak ini',
    'disconnect_question' => 'Apakah Anda yakin untuk memutuskan hubungan :model?',
    'disconnect_yes'      => 'Ya, putuskan hubungan',

    // Messages
    'image_not_saved' => 'Tidak dapat menyimpan gambar',

    'show_on_google_maps' => 'Tampilkan di Google Maps',

    'unsaved_changes' => 'Perubahan belum disimpan',

    'connected_social'   => 'Terhubung dengan kami di jejaring sosial',
    'open_source'        => 'Sumber terbuka di bawah',
    'licence'            => 'Lisensi MIT',
    'free_use'           => 'Gratis digunakan untuk tujuan non-komersial',
    'design_development' => 'Dirancang & dikembangkan',
    'by'                 => 'oleh',

    'open_offcanvas' => 'Buka menu offcanvas',
    'enable_light'   => 'Aktifkan tema terang',
    'enable_dark'    => 'Aktifkan tema gelap',

    'no_data'   => 'Tidak ada data tersedia',
    'no_result' => 'Tidak ada yang cocok dengan kriteria Anda',

    'people_search'             => 'Cari orang di <span class="text-emerald-600"><strong>:scope</strong></span></span>',
    'people_search_placeholder' => 'Masukkan nama ...',
    'people_search_tip'         => 'Cari orang berdasarkan nama keluarga, nama depan, nama lahir, atau nama panggilan.',
    'people_found'              => '<span class="text-emerald-600"><strong>:found</strong></span> ditemukan dengan kata kunci <span class="text-emerald-600"><strong>:keyword</strong></span> dari <span class="text-emerald-600"><strong>:total</strong></span> yang tersedia di <span class="text-emerald-600"><strong>:scope</strong></span>',
    'people_available'          => '<span class="text-emerald-600"><strong>:total</strong></span> tersedia di <span class="text-emerald-600"><strong>:scope</strong></span></span>',

    'people_search_help_1'  => 'Sistem akan mencari nama pada bidang <b class="text-emerald-600">nama keluarga</b>, <b class="text-emerald-600">nama depan</b>, <b class="text-emerald-600">nama lahir</b> dan <b class="text-emerald-600">nama panggilan</b>.',
    'people_search_help_2'  => 'Cara mencari :',
    'people_search_help_2a' => 'Ketik bagian apa pun dari nama untuk menemukan kecocokan <span class="italic">(mis., "Kennedy")</span>',
    'people_search_help_2b' => 'Cari beberapa kata untuk mempersempit hasil <span class="italic">(mis., "John Kennedy")</span>',
    'people_search_help_2c' => 'Urutan kata tidak berpengaruh <span class="italic">("Kennedy John" = "John Kennedy")</span>',
    'people_search_help_2d' => 'Gunakan tanda kutip untuk nama yang memiliki spasi <span class="italic">(mis., "John Fitzgerald Jr.")</span>',
    'people_search_help_2e' => 'Mulai dengan <b class="text-emerald-600">%</b> untuk pencarian substring <span class="italic">(mis., "%Jr.")</span>',
    'people_search_help_3'  => 'Catatan : <b class="text-emerald-600">Pencarian pendek (1–2 karakter) atau wildcard (%) dapat memakan waktu lebih lama pada pohon keluarga besar</b>.',

    'unauthorized_access' => 'Akses tidak sah.',

    'terminal' => 'Terminal',

    'event_added'       => 'ditambahkan',
    'event_created'     => 'dibuat',
    'event_updated'     => 'diperbarui',
    'event_deleted'     => 'dihapus',
    'event_invited'     => 'diundang',
    'event_removed'     => 'dikeluarkan',
    'event_transferred' => 'dipindahkan',

    'settings' => 'Pengaturan',

    'people_logbook' => 'Buku log orang',
    'team_logbook'   => 'Buku log tim',

    'under_construction' => 'Dalam Pengembangan',
    'demonstration'      => 'Demonstrasi',

    'password_generator'   => 'Pembuat Kata Sandi',
    'password_length'      => 'Panjang kata sandi',
    'use_numbers'          => 'Gunakan angka',
    'use_symbols'          => 'Gunakan simbol',
    'generate'             => 'Hasilkan',
    'copy_to_clipboard'    => 'Salin ke papan klip',
    'copied_to_clipboard'  => 'Disalin ke papan klip!',
    'password_very_weak'   => 'Sangat lemah',
    'password_weak'        => 'Lemah',
    'password_moderate'    => 'Sedang',
    'password_strong'      => 'Kuat',
    'password_very_strong' => 'Sangat kuat',
    'check_breach'         => 'Periksa apakah alamat email Anda ada dalam pelanggaran data',
];
