<?php

declare(strict_types=1);

return [
    'backup'        => 'Backup',
    'backups'       => 'Cópias de segurança',
    'no_data'       => 'Nenhum backup disponível.',
    'create'        => 'Novo backup',
    'download'      => 'Download',
    'delete'        => 'Excluir',
    'delete_backup' => 'este backup',
    'id'            => '#',
    'file'          => 'Arquivo',
    'size'          => 'Tamanho',
    'date'          => 'Data',
    'age'           => 'Idade',
    'actions'       => 'Ações',
    'backup_daily'  => 'Os backups são criados automaticamente diariamente (às ' . config('app.backup.daily_run') . ' horas).',
    'backup_email'  => 'Um e-mail será enviado para o endereço de e-mail do seu aplicativo após cada backup.',
    'backup_cron_1' => 'Os backups podem ser automatizados (executados diariamente) emitindo o seguinte cron job em seu servidor de produção :',
    'backup_cron_2' => '* * * * * cd /caminho_para_seu_aplicativo && agendamento do artesão php:run >> /dev/null 2>&1',
    'created'       => 'O novo backup foi salvo.',
    'deleted'       => 'é excluído.',
    'downloading'   => 'O download foi iniciado.',
    'failed'        => 'O backup falhou.',
    'not_found'     => 'O backup não foi encontrado.',
];
