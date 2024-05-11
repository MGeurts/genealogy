<?php

namespace App\Livewire\Backups;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Manage extends Component
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

    public $backups;

    public $backup_to_delete = '';

    public $deleteConfirmed = false;

    // -----------------------------------------------------------------------
    public function confirmDeletion(string $file_name): void
    {
        $this->backup_to_delete = $file_name;

        $this->deleteConfirmed = true;
    }

    public function mount(): void
    {
        $this->backups = collect();

        $disk  = Storage::disk(config('app.backup_disk'));
        $files = $disk->files(config('backup.backup.name'));

        // make a collection of existing backup files, with their filesize and creation date
        foreach ($files as $file) {
            // only take zip files into account
            if (substr($file, -4) == '.zip' && $disk->exists($file)) {
                $this->backups->push([
                    'file_name'    => str_replace(config('backup.backup.name') . '/', '', $file),
                    'file_size'    => Number::fileSize($disk->size($file), 2),
                    'date_created' => Carbon::createFromTimestamp($disk->lastModified($file))->format('d-m-Y H:i:s'),
                    'date_ago'     => Carbon::createFromTimestamp($disk->lastModified($file))->diffForHumans(Carbon::now()),
                ]);
            }
        }

        $this->backups = $this->backups->sortByDesc('date_created');
    }

    public function create()
    {
        if (! defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'r'));
        }

        $exitCode = Artisan::call('backup:run --only-db');
        $output   = Artisan::output();

        if ($exitCode == 0) {
            Log::info("Backup (Manually) -- Backup started \r\n" . $output);

            $this->toast()->success(__('backup.backup'), __('backup.created'))->flash()->send();
        } else {
            Log::error("Backup (Manually) -- Backup failed \r\n" . $output);

            $this->toast()->error(__('backup.backup'), __('backup.failed'))->flash()->send();
        }

        $this->redirect('/backups');
    }

    public function download(string $file_name)
    {
        $disk = Storage::disk(config('app.backup_disk'));
        $file = config('backup.backup.name') . '/' . $file_name;

        if ($disk->exists($file)) {
            $this->toast()->success(__('backup.backup'), __('backup.downloading'))->send();

            return Storage::download(config('app.backup_disk') . '/' . $file);
        } else {
            $this->toast()->error(__('backup.backup'), __('backup.not_found'))->send();
        }
    }

    public function deleteBackup()
    {
        $disk = Storage::disk(config('app.backup_disk'));

        if ($disk->exists(config('backup.backup.name') . '/' . $this->backup_to_delete)) {
            $disk->delete(config('backup.backup.name') . '/' . $this->backup_to_delete);

            $this->toast()->success(__('backup.backup'), $this->backup_to_delete . ' ' . __('backup.deleted'))->expandable(false)->flash()->send();
        } else {
            $this->toast()->error(__('backup.backup'), __('backup.not_found'))->flash()->send();
        }

        $this->redirect('/backups');
    }

    // -----------------------------------------------------------------------
    public function render()
    {
        return view('livewire.backups.manage');
    }
}
