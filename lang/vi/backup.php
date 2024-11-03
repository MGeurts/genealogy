<?php

return [
    // Labels
    'backup'  => 'Sao lưu',
    'backups' => 'Các bản sao lưu',
    'no_data' => 'Không có bản sao lưu nào khả dụng.',

    // Actions
    'create'        => 'Sao lưu mới',
    'download'      => 'Tải xuống',
    'delete'        => 'Xóa',
    'delete_backup' => 'bản sao lưu này',

    // Attributes
    'id'      => '#',
    'file'    => 'Tệp',
    'size'    => 'Kích thước',
    'date'    => 'Ngày',
    'age'     => 'Tuổi',
    'actions' => 'Hành động',

    // Comments
    'backup_daily'  => 'Các bản sao lưu được tạo tự động hàng ngày (vào lúc ' . config('app.backup_daily_run') . ' giờ).',
    'backup_email'  => 'Một email sẽ được gửi đến địa chỉ email của ứng dụng của bạn sau mỗi lần sao lưu.',
    'backup_cron_1' => 'Các bản sao lưu có thể được tự động hóa (chạy hàng ngày) bằng cách phát hành cron job sau trên máy chủ sản xuất của bạn :',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan schedule:run >> /dev/null 2>&1',

    // Messages
    'created'     => 'Bản sao lưu mới đã được lưu.',
    'deleted'     => 'đã bị xóa.',
    'downloading' => 'Tải xuống đã bắt đầu.',
    'failed'      => 'Sao lưu thất bại.',
    'not_found'   => 'Bản sao lưu không được tìm thấy.',
];
