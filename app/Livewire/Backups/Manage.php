<?php

namespace App\Livewire\Backups;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Manage extends Component
{
    // ------------------------------------------------------------------------------
    // To make this BACKUP controller work, you need to :
    // ------------------------------------------------------------------------------
    //      1. add and configure this to your .env :
    //
    //          BACKUP_DISK="backups"
    //          BACKUP_DAILY_CLEANUP="22:30"
    //          BACKUP_DAILY_RUN="23:00"
    //          BACKUP_MAIL_ADDRESS="webmaster@yourdomain.com"
    //
    //      2. configure this to a working mail system in your .env :
    //          MAIL_MAILER=smtp
    //          MAIL_HOST=mailpit
    //          MAIL_PORT=1025
    //          MAIL_USERNAME=null
    //          MAIL_PASSWORD=null
    //          MAIL_ENCRYPTION=null
    //          MAIL_FROM_ADDRESS="no-reply@yourdomain.com"
    //          MAIL_FROM_NAME="${APP_NAME}"
    // ------------------------------------------------------------------------------
    //      2. add this to your config/filesystem.php :
    //
    //          env('BACKUP_DISK', 'backups') => [
    //              'driver' => 'local',
    //              'root' => storage_path('app/' . env('BACKUP_DISK', 'backups')),
    //              'throw' => false,
    //          ],
    // ------------------------------------------------------------------------------
    //      3. configure this in your config/backup.php :
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
    // ------------------------------------------------------------------------------
    use WireToast;

    public $backups;

    public $backup_to_delete = '';

    public $deleteConfirmed = false;

    public function confirmDeletion(string $file_name)
    {
        $this->backup_to_delete = $file_name;

        $this->deleteConfirmed = true;
    }

    public function mount()
    {
        $this->backups = collect();

        $disk = Storage::disk(env('BACKUP_DISK', 'backups'));
        $files = $disk->files(config('backup.backup.name'));

        // make a collection of existing backup files, with their filesize and creation date
        foreach ($files as $index => $file) {
            // only take zip files into account
            if (substr($file, -4) == '.zip' && $disk->exists($file)) {
                $this->backups->push([
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $file),
                    'file_size' => $this->humanFilesize($disk->size($file)),
                    'date_created' => Carbon::createFromTimestamp($disk->lastModified($file))->format('d-m-Y H:i:s'),
                    'date_ago' => Carbon::createFromTimestamp($disk->lastModified($file))->diffForHumans(Carbon::now()),
                ]);
            }
        }

        $this->backups = $this->backups->sortByDesc('date_created');
    }

    public function create()
    {
        try {
            if (! defined('STDIN')) {
                define('STDIN', fopen('php://stdin', 'r'));
            }

            Artisan::call('backup:run --only-db');
            //Artisan::call('backup:run --only-db', ['--force' => true]);
            //Artisan::queue('backup:run --only-db');
            $output = Artisan::output();

            Log::info("Backup (Manually) -- Backup started \r\n" . $output);

            toast()->info(__('backup.created'), __('backup.backup'))->push();
        } catch (Exception $e) {
            Log::info("Backup (Manually) -- Backup failed \r\n" . $e->getMessage());

            toast()->danger($e->getMessage(), __('backup.backup'))->push();
        }

        $this->mount();
    }

    public function download(string $file_name)
    {
        $disk = Storage::disk(env('BACKUP_DISK', 'backups'));
        $file = config('backup.backup.name') . '/' . $file_name;

        if ($disk->exists($file)) {
            toast()->info(__('backup.downloaded'), __('backup.backup'))->push();

            return Storage::download(env('BACKUP_DISK', 'backups') . '/' . $file);
        } else {
            toast()->warning(__('backup.not_found'), __('backup.backup'))->push();
        }
    }

    public function deleteBackup()
    {
        $disk = Storage::disk(env('BACKUP_DISK', 'backups'));

        if ($disk->exists(config('backup.backup.name') . '/' . $this->backup_to_delete)) {
            $disk->delete(config('backup.backup.name') . '/' . $this->backup_to_delete);

            toast()->success($this->backup_to_delete . '<br/>' . __('backup.deleted'), __('backup.backup'))->doNotSanitize()->push();
        } else {
            toast()->warning(__('backup.not_found'), __('backup.backup'))->push();
        }

        $this->deleteConfirmed = false;

        $this->mount();
    }

    protected function humanFilesize(mixed $bytes, int $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor];
    }

    public function render()
    {
        return view('livewire.backups.manage');
    }
}
