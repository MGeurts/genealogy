<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Person;
use App\Services\Photos\CustomPersonPhotoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Migrates legacy filesystem-backed person photos into the Media Library table.
 *
 * This command reads existing photos from the legacy `photos` disk layout
 * (used by the custom photo driver), creates corresponding Media Library
 * records on the `photos` collection for each person, and optionally deletes
 * the legacy files after successful import. Callers should rely on this to
 * move existing installs to the new Media Library-backed photo driver.
 */
class MigratePersonPhotosToMediaLibrary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:migrate-to-medialibrary
                            {--delete-old : Delete legacy photo files after importing}
                            {--person=* : Limit migration to specific person IDs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate legacy person photos into Spatie Media Library and update the media table';

    public function handle(): int
    {
        $this->info('Starting person photo migration to Media Library...');

        $deleteOld = (bool) $this->option('delete-old');
        /** @var array<int, int|string> $personIds */
        $personIds = $this->option('person');

        $legacyService = new CustomPersonPhotoService();

        $totalPersons   = 0;
        $totalPhotos    = 0;
        $skippedPhotos  = 0;
        $missingFiles   = 0;
        $primaryUpdated = 0;

        $query = Person::query();

        if ($personIds !== []) {
            $query->whereIn('id', $personIds);
        }

        $query->chunkById(100, function ($people) use (
            $legacyService,
            $deleteOld,
            &$totalPersons,
            &$totalPhotos,
            &$skippedPhotos,
            &$missingFiles,
            &$primaryUpdated,
        ): void {
            /** @var \Illuminate\Support\Collection<int, Person> $people */
            foreach ($people as $person) {
                $totalPersons++;

                $legacyPhotos = $legacyService->getOriginalPhotosForExport($person);

                if ($legacyPhotos === []) {
                    continue;
                }

                $this->line("Processing person #{$person->id} ({$person->name}) with " . count($legacyPhotos) . ' legacy photos...');

                foreach ($legacyPhotos as $photoMeta) {
                    $diskPath          = (string) $photoMeta['disk_path'];
                    $legacyFileName    = (string) $photoMeta['file_reference'];
                    $legacyBaseName    = (string) $photoMeta['filename'];
                    $legacyAbsolute    = Storage::disk('photos')->path($diskPath);
                    $legacyPrimaryName = $person->photo;

                    if (! Storage::disk('photos')->exists($diskPath)) {
                        $this->warn("  - Missing file at {$diskPath}, skipping");
                        $missingFiles++;

                        continue;
                    }

                    $alreadyExists = Media::query()
                        ->where('model_type', Person::class)
                        ->where('model_id', $person->id)
                        ->where('collection_name', 'photos')
                        ->where('file_name', $legacyFileName)
                        ->exists();

                    if ($alreadyExists) {
                        $this->line("  - Media for {$legacyFileName} already exists, skipping");
                        $skippedPhotos++;

                        continue;
                    }

                    /** @var Media $media */
                    $media = $person
                        ->addMedia($legacyAbsolute)
                        ->toMediaCollection('photos', 'photos');

                    $totalPhotos++;

                    if ($legacyPrimaryName && $legacyPrimaryName === $legacyBaseName) {
                        $person->photo = (string) $media->id;
                        $person->save();
                        $primaryUpdated++;
                    }

                    if ($deleteOld) {
                        $this->deleteLegacyFilesForPhoto($diskPath);
                    }
                }
            }
        });

        $this->newLine();
        $this->info('Migration finished.');
        $this->line("Persons processed: {$totalPersons}");
        $this->line("Photos imported : {$totalPhotos}");
        $this->line("Photos skipped  : {$skippedPhotos}");
        $this->line("Missing files   : {$missingFiles}");
        $this->line("Primary updated : {$primaryUpdated}");

        if (! $deleteOld) {
            $this->comment('Legacy files were not deleted. Re-run with --delete-old once you have verified the migration.');
        }

        return self::SUCCESS;
    }

    private function deleteLegacyFilesForPhoto(string $diskPath): void
    {
        $disk = Storage::disk('photos');

        $disk->delete($diskPath);

        $directory         = dirname($diskPath);
        $filename          = basename($diskPath);
        $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);

        $variants = [
            "{$directory}/{$filenameWithoutExt}_large.webp",
            "{$directory}/{$filenameWithoutExt}_medium.webp",
            "{$directory}/{$filenameWithoutExt}_small.webp",
        ];

        $disk->delete($variants);
    }
}

