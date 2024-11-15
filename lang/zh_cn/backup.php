<?php

return [
    // 标签
    'backup'  => '备份',
    'backups' => '备份',
    'no_data' => '没有可用的备份。',

    // 操作
    'create'        => '新备份',
    'download'      => '下载',
    'delete'        => '删除',
    'delete_backup' => '删除此备份',

    // 属性
    'id'      => '#',
    'file'    => '文件',
    'size'    => '大小',
    'date'    => '日期',
    'age'     => '年龄',
    'actions' => '操作',

    // 注释
    'backup_daily'  => '备份每天自动创建（在 ' . config('app.backup.daily_run') . ' 点）。',
    'backup_email'  => '每次备份后，系统将发送一封电子邮件到您的应用程序电子邮件地址。',
    'backup_cron_1' => '可以通过在您的生产服务器上运行以下cron任务来自动化备份（每天运行）：',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan schedule:run >> /dev/null 2>&1',

    // 消息
    'created'     => '新的备份已保存。',
    'deleted'     => '已删除。',
    'downloading' => '下载已开始。',
    'failed'      => '备份失败。',
    'not_found'   => '未找到备份。',
];
