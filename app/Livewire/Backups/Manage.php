<?php

declare(strict_types=1);

namespace App\Livewire\Backups;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TallStackUi\Traits\Interactions;

final class Manage extends Component
{
    // -----------------------------------------------------------------------
    // To make this BACKUP controller work, you need to :
    // -----------------------------------------------------------------------
    //      1. install laravel-backup
    //         https://github.com/spatie/laravel-backup
    //
    //      2. add and configure this to your .env :
    //
    //          BACKUP_DISK="backups"
    //          BACKUP_DAILY_CLEANUP="22:30"
    //          BACKUP_DAILY_RUN="23:00"
    //          BACKUP_MAIL_ADDRESS="webmaster@yourdomain.com"
    //
    //      3. configure this to a working mail system in your .env :
    //          MAIL_MAILER=smtp
    //          MAIL_HOST=mailpit
    //          MAIL_PORT=1025
    //          MAIL_USERNAME=null
    //          MAIL_PASSWORD=null
    //          MAIL_ENCRYPTION=null
    //          MAIL_FROM_ADDRESS="no-reply@yourdomain.com"
    //          MAIL_FROM_NAME="${APP_NAME}"
    // -----------------------------------------------------------------------
    //      4. add this to your config/filesystem.php :
    //
    //          env('BACKUP_DISK', 'backups') => [
    //              'driver' => 'local',
    //              'root' => storage_path('app/' . env('BACKUP_DISK', 'backups')),
    //              'throw' => false,
    //          ],
    // -----------------------------------------------------------------------
    //      5. configure this in your config/backup.php :
    //
    //          // backup --> destination --> disks :
    //          'disks' => [
    //              env('BACKUP_DISK', 'backups'),
    //          ]
    //
    //          // backup --> monitor-backups --> disks :
    //          'disks' => [
    //              env('BACKUP_DISK', 'backups'),
    //          ]
    // -----------------------------------------------------------------------
    use Interactions;

    // ------------------------------------------------------------------------------
    public Collection $backups;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $disk = Storage::disk(config('app.backup.disk'));

        $this->backups = collect($disk->files(config('backup.backup.name')))
            ->filter(fn ($file): bool => str_ends_with($file, '.zip') && $disk->exists($file))
            ->map(fn ($file): array => [
                'file_name'    => Str::after($file, config('backup.backup.name') . '/'),
                'file_size'    => Number::fileSize($disk->size($file), 2),
                'date_created' => Carbon::createFromTimestamp($disk->lastModified($file))->format('d-m-Y H:i:s'),
                'date_ago'     => Carbon::createFromTimestamp($disk->lastModified($file))->diffForHumans(),
            ])
            ->sortByDesc('date_created')
            ->values(); // Reset collection keys
    }

    public function create(): void
    {
        $exitCode = Artisan::call('backup:run --only-db');
        $output   = Artisan::output();

        if ($exitCode === 0) {
            Log::info("Backup (Manually) -- Backup started \r\n" . $output);

            $this->toast()->success(__('backup.backup'), __('backup.created'))->flash()->send();
        } else {
            Log::error("Backup (Manually) -- Backup failed \r\n" . $output);

            $this->toast()->error(__('backup.backup'), __('backup.failed'))->flash()->send();
        }

        $this->redirect('/developer/backups');
    }

    public function download(string $file_name): ?StreamedResponse
    {
        $disk = Storage::disk(config('app.backup.disk'));
        $file = config('backup.backup.name') . '/' . $file_name;

        if ($disk->exists($file)) {
            $this->toast()->success(__('backup.backup'), __('backup.downloading'))->send();

            return Storage::download(config('app.backup.disk') . '/' . $file);
        }

        $this->toast()->error(__('backup.backup'), __('backup.not_found'))->send();

        return null;
    }

    public function delete(string $backup_to_delete): void
    {
        $disk = Storage::disk(config('app.backup.disk'));

        if ($disk->exists(config('backup.backup.name') . '/' . $backup_to_delete)) {
            $disk->delete(config('backup.backup.name') . '/' . $backup_to_delete);

            $this->toast()->success(__('backup.backup'), $backup_to_delete . ' ' . __('backup.deleted'))->expandable(false)->flash()->send();
        } else {
            $this->toast()->error(__('backup.backup'), __('backup.not_found'))->flash()->send();
        }

        $this->redirect('/developer/backups');
    }

    public function confirm(string $file_name): void
    {
        $this->dialog()
            ->question(__('app.attention') . '!', __('app.are_you_sure'))
            ->confirm(__('app.delete_yes'))
            ->cancel(__('app.cancel'))
            ->hook([
                'ok' => [
                    'method' => 'delete',
                    'params' => $file_name,
                ],
            ])
            ->send();
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.backups.manage');
    }
}
