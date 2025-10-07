<?php

declare(strict_types=1);

namespace App;

use App\Models\Person;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Throwable;

final class PersonPhotos
{
    private readonly Filesystem $photosDisk;

    private readonly Filesystem $tempDisk;

    private readonly string $personPath;

    private readonly string $personId;

    private readonly string $personIdPrefix;

    private ?array $cachedFiles = null;

    public function __construct(
        private readonly Person $person,
        private readonly ImageManager $imageManager = new ImageManager(new Driver()),
        private ?array $uploadConfig = null,
        ?string $photosDiskName = null,
        ?string $tempDiskName = null
    ) {
        $this->uploadConfig   = $this->uploadConfig ?? config('app.upload_photo');
        $this->photosDisk     = Storage::disk($photosDiskName ?? 'photos');
        $this->tempDisk       = Storage::disk($tempDiskName ?? 'local');
        $this->personPath     = "{$this->person->team_id}/{$this->person->id}";
        $this->personId       = (string) $this->person->id;
        $this->personIdPrefix = $this->personId . '_';
    }

    /**
     * Save multiple photos for the person.
     * Automatically handles directory creation and cache invalidation.
     *
     * @param  array<int, UploadedFile|string>  $photos
     * @return int|null Number of successfully saved photos, null if none provided
     */
    public function save(array $photos): ?int
    {
        if (empty($photos)) {
            return null;
        }

        $this->ensurePhotoDirectoryExist();

        $lastIndex  = $this->getLastImageIndex();
        $savedCount = 0;

        foreach ($photos as $photo) {
            $lastIndex++;
            if ($this->savePhoto($photo, $lastIndex)) {
                $savedCount++;
            }
        }

        $this->invalidateCache();
        $this->cleanupTemporaryFiles();

        return $savedCount > 0 ? $savedCount : null;
    }

    /**
     * Get the photo URL for a specific size.
     * Uses cached file operations for better performance.
     *
     * @param  int  $index  The photo index (1-based)
     * @param  string  $sizeKey  Size variant: 'original', 'large', 'medium', 'small'
     * @return string|null The photo URL or null if not found
     */
    public function getPhotoUrl(int $index, string $sizeKey = 'large'): ?string
    {
        $filename = $this->findPhotoFile($index, $sizeKey);

        if (! $filename) {
            return null;
        }

        $path = $this->personPath . '/' . $filename;

        // Handle different disk types
        if (method_exists($this->photosDisk, 'url')) {
            return $this->photosDisk->url($path);
        }

        // Fallback for disks that don't support URLs
        return Storage::url($path);
    }

    /**
     * Delete all photos for this person.
     * Removes the entire person directory and invalidates cache.
     *
     * @return bool True if deletion succeeded or directory didn't exist
     */
    public function deleteAll(): bool
    {
        try {
            if ($this->photosDisk->exists($this->personPath)) {
                $result = $this->photosDisk->deleteDirectory($this->personPath);
                $this->invalidateCache();

                return $result;
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Failed to delete all photos', [
                'person_id' => $this->person->id,
                'team_id'   => $this->person->team_id,
                'error'     => $e->getMessage(),
                'exception' => $e,
            ]);

            return false;
        }
    }

    /**
     * Delete a specific photo by index.
     * Removes original and all size variants (large, medium, small) and invalidates cache.
     *
     * @param  int  $index  The photo index to delete
     * @return bool True if at least one file was deleted
     */
    public function delete(int $index): bool
    {
        try {
            $files         = $this->getPersonFiles();
            $indexPattern  = sprintf('_%03d_', $index);
            $filesToDelete = [];

            foreach ($files as $file) {
                $basename = basename($file);
                if (str_contains($basename, $indexPattern)) {
                    $filesToDelete[] = $file;
                }
            }

            if (empty($filesToDelete)) {
                return false;
            }

            foreach ($filesToDelete as $file) {
                $this->photosDisk->delete($file);
            }

            $this->invalidateCache();

            return true;
        } catch (Throwable $e) {
            Log::error('Failed to delete specific photo', [
                'person_id'   => $this->person->id,
                'team_id'     => $this->person->team_id,
                'photo_index' => $index,
                'error'       => $e->getMessage(),
                'exception'   => $e,
            ]);

            return false;
        }
    }

    /**
     * Count total photos for this person.
     * Counts original files only (without size suffix).
     *
     * @return int Number of photos
     */
    public function countPhotos(): int
    {
        // allthough we could call $this->person->countPhotos(), we prefer to use cached files
        $files = $this->getPersonFiles();

        if (empty($files)) {
            return 0;
        }

        // Derive valid extensions from app config
        $validExtensions = collect(config('app.upload_photo_accept'))
            ->keys()
            ->map(fn ($mime) => Str::after($mime, '/'))
            ->push('jpg') // ensure "jpg" is included
            ->unique()
            ->toArray();

        $baseNames = collect($files)
            ->filter(function ($file) use ($validExtensions) {
                $extension = mb_strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $ignored   = ['gitignore', 'db'];

                return in_array($extension, $validExtensions) && ! in_array($extension, $ignored);
            })
            ->map(fn ($file) => pathinfo($file, PATHINFO_FILENAME))
            ->map(fn ($name) => preg_replace('/_(large|medium|small)$/i', '', $name))
            ->unique();

        return $baseNames->count();
    }

    /**
     * Get files from cache or filesystem.
     * Caches file list to reduce filesystem operations.
     *
     * @param  bool  $forceRefresh  Force reload from filesystem
     * @return array<string> Array of file paths
     */
    private function getPersonFiles(bool $forceRefresh = false): array
    {
        if ($this->cachedFiles === null || $forceRefresh) {
            if (! $this->photosDisk->exists($this->personPath)) {
                $this->cachedFiles = [];
            } else {
                $this->cachedFiles = $this->photosDisk->files($this->personPath);
            }
        }

        return $this->cachedFiles;
    }

    /**
     * Invalidate file cache.
     * Call this after any file system modifications (save/delete operations).
     */
    private function invalidateCache(): void
    {
        $this->cachedFiles = null;
    }

    /**
     * Ensure person's photo directory exists.
     * Creates the full directory path recursively if needed.
     */
    private function ensurePhotoDirectoryExist(): void
    {
        // Create the full path at once (recursive)
        if (! $this->photosDisk->exists($this->personPath)) {
            $this->photosDisk->makeDirectory($this->personPath, 0755, true);
        }
    }

    /**
     * Get the highest index number for existing photos.
     * Optimized to work with person-specific directories where all files belong to this person.
     *
     * @return int The highest photo index (0 if no photos exist)
     */
    private function getLastImageIndex(): int
    {
        $files = $this->getPersonFiles();

        if (empty($files)) {
            return 0;
        }

        $maxIndex = 0;

        foreach ($files as $file) {
            $basename = basename($file);

            // Skip sized versions - only check original files
            if (str_contains($basename, '_large.') ||
                str_contains($basename, '_medium.') ||
                str_contains($basename, '_small.')) {
                continue;
            }

            // Extract index from filename: personId_index_timestamp.ext
            $parts = explode('_', $basename);

            // We expect at least 3 parts: {personId}_{index}_{timestamp}.ext
            if (count($parts) >= 3 && is_numeric($parts[1])) {
                $maxIndex = max($maxIndex, (int) $parts[1]);
            }
        }

        return $maxIndex;
    }

    /**
     * Find a photo file for a specific index and size variant.
     * Uses cached file list and optimized pattern matching.
     *
     * @param  int  $index  The photo index to find
     * @param  string  $sizeKey  Size variant: 'original', 'large', 'medium', 'small'
     * @return string|null The filename or null if not found
     */
    private function findPhotoFile(int $index, string $sizeKey): ?string
    {
        $files        = $this->getPersonFiles();
        $indexPattern = sprintf('_%03d_', $index);

        foreach ($files as $file) {
            $basename = basename($file);

            if (! str_contains($basename, $indexPattern)) {
                continue;
            }

            // Check size match
            if ($sizeKey === 'original') {
                // Original has no size suffix
                if (! str_contains($basename, '_large.') &&
                    ! str_contains($basename, '_medium.') &&
                    ! str_contains($basename, '_small.')) {
                    return $basename;
                }
            } else {
                // Check for specific size variant
                if (str_contains($basename, "_{$sizeKey}.")) {
                    return $basename;
                }
            }
        }

        return null;
    }

    /**
     * Save a single photo: original untouched + 3 processed variants.
     * Processes the photo and updates person record if it's the first photo.
     *
     * @param  UploadedFile|string  $photo  The photo to save
     * @param  int  $index  The index number for this photo
     * @return bool True if photo was saved successfully
     */
    private function savePhoto(UploadedFile|string $photo, int $index): bool
    {
        $timestamp = now()->format('YmdHis');

        try {
            // Save original file untouched
            $originalSaved = $this->saveOriginalFile($photo, $index, $timestamp);

            if (! $originalSaved) {
                return false;
            }

            // Process and save size variants
            $variantsSaved = $this->processAndSaveVariants($photo, $index, $timestamp);

            if ($originalSaved && empty($this->person->photo)) {
                // Save the original filename for reference (without extension)
                $this->person->update([
                    'photo' => $this->makeFilename($index, $timestamp, 'original', false),
                ]);
            }

            return $originalSaved && $variantsSaved;
        } catch (Throwable $e) {
            Log::error('Failed to save photo', [
                'person_id'   => $this->person->id,
                'team_id'     => $this->person->team_id,
                'photo_index' => $index,
                'error'       => $e->getMessage(),
                'exception'   => $e,
            ]);

            return false;
        }
    }

    /**
     * Save the original uploaded file without any processing.
     * Preserves the original format and quality.
     *
     * @param  UploadedFile|string  $photo  The source photo
     * @param  int  $index  The photo index
     * @param  string  $timestamp  The timestamp for filename generation
     * @return bool True if original was saved successfully
     */
    private function saveOriginalFile(UploadedFile|string $photo, int $index, string $timestamp): bool
    {
        try {
            // Determine original extension
            $extension = 'jpg'; // default fallback

            if ($photo instanceof UploadedFile) {
                $extension = $photo->getClientOriginalExtension() ?: $photo->extension() ?: 'jpg';
            } elseif (is_string($photo) && file_exists($photo)) {
                $extension = pathinfo($photo, PATHINFO_EXTENSION) ?: 'jpg';
            }

            $filename = $this->makeFilename($index, $timestamp, 'original', true, $extension);
            $path     = $this->personPath . '/' . $filename;

            // Save original file directly without any processing
            if ($photo instanceof UploadedFile) {
                $content = file_get_contents($photo->getRealPath());
            } else {
                $content = file_get_contents($photo);
            }

            $this->photosDisk->put($path, $content);

            return true;
        } catch (Throwable $e) {
            Log::error('Failed to save original file', [
                'person_id'   => $this->person->id,
                'team_id'     => $this->person->team_id,
                'photo_index' => $index,
                'error'       => $e->getMessage(),
                'exception'   => $e,
            ]);

            return false;
        }
    }

    /**
     * Process image in each configured size and save to the photos disk.
     * Creates all size variants (large, medium, small) with watermark if enabled.
     *
     * @param  UploadedFile|string  $photo  The source photo
     * @param  int  $index  The photo index
     * @param  string  $timestamp  The timestamp for filename generation
     * @return bool True if at least one size variant was saved successfully
     */
    private function processAndSaveVariants(UploadedFile|string $photo, int $index, string $timestamp): bool
    {
        $sizes    = $this->uploadConfig['sizes'] ?? [];
        $savedAny = false;

        try {
            $original = $this->imageManager->read($photo);
        } catch (Throwable $e) {
            Log::error('Failed to read image file for variants', [
                'person_id'   => $this->person->id,
                'team_id'     => $this->person->team_id,
                'photo_index' => $index,
                'error'       => $e->getMessage(),
                'exception'   => $e,
            ]);

            return false;
        }

        foreach ($sizes as $sizeKey => $dimensions) {
            try {
                $image = clone $original;

                $image->scaleDown(
                    width: $dimensions['width'],
                    height: $dimensions['height']
                );

                $this->applyWatermark($image);

                $filename = $this->makeFilename($index, $timestamp, $sizeKey);
                $path     = $this->personPath . '/' . $filename;

                // Use size-specific quality or fall back to 85
                $quality = $dimensions['quality'] ?? 85;

                $this->photosDisk->put(
                    $path,
                    $image->toWebp(quality: $quality)
                );

                $savedAny = true;
            } catch (Throwable $e) {
                Log::error('Failed to process photo size variant', [
                    'person_id'   => $this->person->id,
                    'team_id'     => $this->person->team_id,
                    'photo_index' => $index,
                    'size_key'    => $sizeKey,
                    'dimensions'  => $dimensions,
                    'error'       => $e->getMessage(),
                    'exception'   => $e,
                ]);
            }
        }

        return $savedAny;
    }

    /**
     * Build filename using the naming convention: personId_index_timestamp[_size].ext
     *
     * @param  int  $index  The photo index (zero-padded to 3 digits)
     * @param  string  $timestamp  The timestamp string
     * @param  string  $sizeKey  Size variant: 'original', 'large', 'medium', 'small'
     * @param  bool  $includeExtension  Whether to include extension
     * @param  string  $extension  File extension (default 'webp')
     * @return string The generated filename
     */
    private function makeFilename(
        int $index,
        string $timestamp,
        string $sizeKey,
        bool $includeExtension = true,
        string $extension = 'webp'
    ): string {
        $suffix = $sizeKey !== 'original' ? "_{$sizeKey}" : '';

        $filename = sprintf(
            '%s_%03d_%s%s',
            $this->personId,
            $index,
            $timestamp,
            $suffix
        );

        if ($includeExtension) {
            $filename .= '.' . $extension;
        }

        return $filename;
    }

    /**
     * Apply watermark to image if enabled in configuration.
     * Watermark is placed at bottom-left with 5px margins.
     *
     * @param  mixed  $image  The Intervention Image instance
     */
    private function applyWatermark($image): void
    {
        if (! ($this->uploadConfig['add_watermark'] ?? false)) {
            return;
        }

        $path = public_path('img/watermark.png');

        if (! file_exists($path)) {
            Log::warning('Watermark file missing', [
                'watermark_path' => $path,
                'person_id'      => $this->person->id,
            ]);

            return;
        }

        try {
            $image->place($path, 'bottom-left', 5, 5);
        } catch (Throwable $e) {
            Log::warning('Failed to apply watermark', [
                'person_id'      => $this->person->id,
                'watermark_path' => $path,
                'error'          => $e->getMessage(),
            ]);
        }
    }

    /**
     * Cleanup old temporary files from Livewire uploads.
     * Removes files older than 24 hours from the livewire-tmp directory.
     */
    private function cleanupTemporaryFiles(): void
    {
        try {
            $cutoff = now()->subDay()->timestamp;

            if (! $this->tempDisk->exists('livewire-tmp')) {
                return;
            }

            $files = array_filter(
                $this->tempDisk->files('livewire-tmp'),
                fn ($file) => $this->tempDisk->lastModified($file) <= $cutoff
            );

            if ($files) {
                $this->tempDisk->delete($files);
            }
        } catch (Throwable $e) {
            Log::warning('Failed to cleanup temporary files', [
                'temp_disk'        => get_class($this->tempDisk),
                'cutoff_timestamp' => $cutoff ?? null,
                'error'            => $e->getMessage(),
            ]);
        }
    }
}
