<?php

return [
    // 标签
    'backup'  => '备份',
    'backups' => '备份',
    'no_data' => '没有可用的备份。',

    // 操作
    'create'        => '新建备份',
    'download'      => '下载',
    'delete'        => '删除',
    'delete_backup' => '这个备份',

    // 属性
    'id'      => '#',
    'file'    => '文件',
    'size'    => '大小',
    'date'    => '日期',
    'age'     => '年龄',
    'actions' => '操作',

    // 注释
    'backup_daily'  => '备份会每天自动创建（在 ' . config('app.backup_daily_run') . ' 小时）。',
    'backup_email'  => '每次备份后，会发送一封电子邮件到您的应用程序电子邮件地址。',
    'backup_cron_1' => '备份可以通过在生产服务器上运行以下cron作业自动化（每日运行）：',
    'backup_cron_2' => '* * * * * cd /path_to_your_application && php artisan schedule:run >> /dev/null 2>&1',

    // 消息
    'created'     => '新备份已保存。',
    'deleted'     => '已删除。',
    'downloading' => '下载已开始。',
    'failed'      => '备份失败。',
    'not_found'   => '未找到备份。',
];
