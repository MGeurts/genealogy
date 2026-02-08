<?php

declare(strict_types=1);

namespace App;

use App\Models\Person;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use RuntimeException;
use Throwable;

final class PersonPhotos
{
    private readonly Filesystem $photosDisk;

    private readonly Filesystem $tempDisk;

    private readonly string $personPath;

    private readonly string $personId;

    /** @var array<int, string>|null */
    private ?array $cachedFiles = null;

    /**
     * @param  array{sizes?: array<string, array{width: int, height: int, quality?: int}>, add_watermark?: bool}|null  $uploadConfig
     */
    public function __construct(
        private readonly Person $person,
        private readonly ImageManager $imageManager = new ImageManager(new Driver()),
        private ?array $uploadConfig = null,
        ?string $photosDiskName = null,
        ?string $tempDiskName = null
    ) {
        $this->uploadConfig = $this->uploadConfig ?? config('app.upload_photo');
        $this->photosDisk   = Storage::disk($photosDiskName ?? 'photos');
        $this->tempDisk     = Storage::disk($tempDiskName ?? 'local');
        $this->personPath   = "{$this->person->team_id}/{$this->person->id}";
        $this->personId     = (string) $this->person->id;
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

        return $savedCount ?: null;
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

        if (method_exists($this->photosDisk, 'url')) {
            return $this->photosDisk->url($path);
        }

        return Storage::url($path);
    }

    /**
     * Delete all photos for this person.
     * Removes the entire person directory, invalidates cache, and clears the person's photo attribute.
     *
     * @return bool True if deletion succeeded or directory didn't exist
     */
    public function deleteAll(): bool
    {
        try {
            if ($this->photosDisk->exists($this->personPath)) {
                $result = $this->photosDisk->deleteDirectory($this->personPath);

                if ($result) {
                    $this->invalidateCache();
                    $this->clearPrimaryPhoto();
                }

                return $result;
            }

            // Directory doesn't exist, ensure photo attribute is cleared
            $this->clearPrimaryPhoto();

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
     * If the deleted photo was primary, sets a new primary or clears the attribute if no photos remain.
     *
     * @param  int  $index  The photo index to delete
     * @return bool True if at least one file was deleted
     */
    public function delete(int $index): bool
    {
        try {
            $files          = $this->getPersonFiles();
            $indexPattern   = sprintf('_%03d_', $index);
            $filesToDelete  = [];
            $deletedPrimary = false;

            foreach ($files as $file) {
                $basename = basename($file);
                if (str_contains($basename, $indexPattern)) {
                    $filesToDelete[] = $file;

                    // Check if we're deleting the primary photo
                    if ($this->isOriginalPhoto($basename)) {
                        $filename = pathinfo($basename, PATHINFO_FILENAME);
                        if ($filename === $this->person->photo) {
                            $deletedPrimary = true;
                        }
                    }
                }
            }

            if (empty($filesToDelete)) {
                return false;
            }

            $this->photosDisk->delete($filesToDelete);
            $this->invalidateCache();

            // Handle primary photo management
            if ($deletedPrimary) {
                $this->setNewPrimaryPhoto();
            }

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
     * Check if a photo exists by filename.
     *
     * @param  string  $photoFilename  The photo filename (without extension)
     */
    public function photoExists(string $photoFilename): bool
    {
        $files = $this->getPersonFiles();

        foreach ($files as $file) {
            $basename = basename($file);
            $filebase = pathinfo($basename, PATHINFO_FILENAME);

            if ($filebase === $photoFilename && $this->isOriginalPhoto($basename)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all original photos with metadata.
     *
     * Return an array of photo data with URLs for all size variants
     *
     * @return array<int, array{name: string, extension: string, is_primary: bool, url_original: string, url_large: string, url_medium: string, url_small: string}>
     */
    public function getAllPhotos(): array
    {
        $files  = $this->getPersonFiles();
        $photos = [];

        foreach ($files as $file) {
            $basename = basename($file);

            if (! $this->isOriginalPhoto($basename)) {
                continue;
            }

            $filename = pathinfo($basename, PATHINFO_FILENAME);
            $basePath = $this->personPath;

            $photos[] = [
                'name'         => $filename,
                'extension'    => pathinfo($basename, PATHINFO_EXTENSION),
                'is_primary'   => $filename === $this->person->photo,
                'url_original' => $this->photosDisk->url("{$basePath}/{$basename}"),
                'url_large'    => $this->photosDisk->url("{$basePath}/{$filename}_large.webp"),
                'url_medium'   => $this->photosDisk->url("{$basePath}/{$filename}_medium.webp"),
                'url_small'    => $this->photosDisk->url("{$basePath}/{$filename}_small.webp"),
            ];
        }

        return $photos;
    }

    /**
     * Set a new primary photo or clear if no photos exist.
     * Automatically selects the first available photo or clears the attribute.
     */
    public function setNewPrimaryPhoto(): void
    {
        try {
            $files      = $this->getPersonFiles(true); // Force refresh
            $firstPhoto = null;

            foreach ($files as $file) {
                $basename = basename($file);

                if (! $this->isOriginalPhoto($basename)) {
                    continue;
                }

                $firstPhoto = pathinfo($basename, PATHINFO_FILENAME);
                break;
            }

            $this->person->update(['photo' => $firstPhoto]);
        } catch (Throwable $e) {
            Log::error('Failed to set new primary photo', [
                'person_id' => $this->person->id,
                'team_id'   => $this->person->team_id,
                'error'     => $e->getMessage(),
                'exception' => $e,
            ]);
        }
    }

    /**
     * Clear the person's primary photo attribute.
     */
    private function clearPrimaryPhoto(): void
    {
        try {
            $this->person->update(['photo' => null]);
        } catch (Throwable $e) {
            Log::error('Failed to clear person photo attribute', [
                'person_id' => $this->person->id,
                'team_id'   => $this->person->team_id,
                'error'     => $e->getMessage(),
            ]);
        }
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
        if (! $this->photosDisk->exists($this->personPath)) {
            $this->photosDisk->makeDirectory($this->personPath);
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

            if (! $this->isOriginalPhoto($basename)) {
                continue;
            }

            $parts = explode('_', $basename);

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
        $files = $this->getPersonFiles();

        if (empty($files)) {
            return null;
        }

        $indexPattern = sprintf('_%03d_', $index);
        $prefix       = $this->personId . $indexPattern;

        foreach ($files as $file) {
            $basename = basename($file);

            if (! str_starts_with($basename, $prefix)) {
                continue;
            }

            $isMatch = match ($sizeKey) {
                'original' => $this->isOriginalPhoto($basename),
                default    => str_contains($basename, "_{$sizeKey}.")
            };

            if ($isMatch) {
                return $basename;
            }
        }

        return null;
    }

    /**
     * Save a single photo: original untouched + 3 processed variants.
     * Processes the photo and updates person record if it's the first photo.
     * OPTIMIZED: Reads file content only once and reuses for both operations.
     *
     * @param  UploadedFile|string  $photo  The photo to save
     * @param  int  $index  The index number for this photo
     * @return bool True if photo was saved successfully
     */
    private function savePhoto(UploadedFile|string $photo, int $index): bool
    {
        try {
            if ($photo instanceof UploadedFile && ! $this->isValidImage($photo)) {
                Log::warning('Invalid image file rejected', [
                    'person_id' => $this->person->id,
                    'filename'  => $photo->getClientOriginalName(),
                ]);

                return false;
            }

            $fileContent = $this->getFileContent($photo);
            $extension   = $this->getFileExtension($photo);
            $timestamp   = (string) time();

            $originalSaved = $this->saveOriginalFile($fileContent, $extension, $index, $timestamp);

            if (! $originalSaved) {
                return false;
            }

            $variantsSaved = $this->processAndSaveVariants($fileContent, $index, $timestamp);

            if (empty($this->person->photo)) {
                $this->person->update([
                    'photo' => $this->makeFilename($index, $timestamp, 'original', false),
                ]);
            }

            return $variantsSaved;
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
     * OPTIMIZED: Accepts pre-read file content instead of reading again.
     *
     * @param  string  $fileContent  The file content (binary)
     * @param  string  $extension  The file extension
     * @param  int  $index  The photo index
     * @param  string  $timestamp  The timestamp for filename generation
     * @return bool True if original was saved successfully
     */
    private function saveOriginalFile(string $fileContent, string $extension, int $index, string $timestamp): bool
    {
        try {
            $filename = $this->makeFilename($index, $timestamp, 'original', true, $extension);
            $path     = $this->personPath . '/' . $filename;

            $this->photosDisk->put($path, $fileContent);

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
     * OPTIMIZED: Accepts pre-read file content instead of reading again.
     *
     * @param  string  $fileContent  The file content (binary)
     * @param  int  $index  The photo index
     * @param  string  $timestamp  The timestamp for filename generation
     * @return bool True if at least one size variant was saved successfully
     */
    private function processAndSaveVariants(string $fileContent, int $index, string $timestamp): bool
    {
        $sizes    = $this->uploadConfig['sizes'] ?? [];
        $savedAny = false;

        try {
            $original = $this->imageManager->read($fileContent);
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

                $quality = $dimensions['quality'] ?? 85;

                $this->photosDisk->put(
                    $path,
                    (string) $image->toWebp(quality: $quality)
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

    /**
     * Get file content from UploadedFile or file path.
     * Centralizes file reading logic.
     *
     * @return string File content as binary string
     *
     * @throws RuntimeException If file cannot be read
     */
    private function getFileContent(UploadedFile|string $photo): string
    {
        if ($photo instanceof UploadedFile) {
            $content = file_get_contents($photo->getRealPath());

            if ($content === false) {
                throw new RuntimeException("Failed to read uploaded file: {$photo->getClientOriginalName()}");
            }

            return $content;
        }

        $content = file_get_contents($photo);

        if ($content === false) {
            throw new RuntimeException("Failed to read file: {$photo}");
        }

        return $content;
    }

    /**
     * Determine file extension from UploadedFile or file path.
     *
     * @return string File extension (default: 'jpg')
     */
    private function getFileExtension(UploadedFile|string $photo): string
    {
        $allowedExtensions = config('app.upload_photo_validation.extensions');

        if ($photo instanceof UploadedFile) {
            $extension = mb_strtolower($photo->getClientOriginalExtension() ?: $photo->extension() ?: 'jpg');

            return in_array($extension, $allowedExtensions) ? $extension : 'jpg';
        }

        if (file_exists($photo)) {
            $extension = mb_strtolower(pathinfo($photo, PATHINFO_EXTENSION) ?: 'jpg');

            return in_array($extension, $allowedExtensions) ? $extension : 'jpg';
        }

        return 'jpg';
    }

    /**
     * Check if a filename represents an original photo (not a sized version).
     *
     * @param  string  $basename  The filename to check
     */
    private function isOriginalPhoto(string $basename): bool
    {
        return ! str_contains($basename, '_large.')
            && ! str_contains($basename, '_medium.')
            && ! str_contains($basename, '_small.');
    }

    /**
     * Validate that uploaded file is a genuine image
     */
    private function isValidImage(UploadedFile $file): bool
    {
        // Check 1: Verify MIME type matches config
        $mimeType     = $file->getMimeType();
        $allowedMimes = array_keys(config('app.upload_photo_accept'));

        if (! in_array($mimeType, $allowedMimes)) {
            Log::warning('Invalid MIME type detected', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'mime'      => $mimeType,
            ]);

            return false;
        }

        // Check 2: Verify file is actually an image using getimagesize
        try {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo === false) {
                Log::warning('File failed getimagesize validation', [
                    'person_id' => $this->person->id,
                    'file'      => $file->getClientOriginalName(),
                ]);

                return false;
            }

            // Verify the image type matches expected types
            $allowedImageTypes = config('app.upload_photo_validation.image_types');

            if (! in_array($imageInfo[2], $allowedImageTypes)) {
                Log::warning('Image type not allowed', [
                    'person_id' => $this->person->id,
                    'file'      => $file->getClientOriginalName(),
                    'type'      => $imageInfo[2],
                ]);

                return false;
            }
        } catch (Exception $e) {
            Log::error('Error validating image', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'error'     => $e->getMessage(),
            ]);

            return false;
        }

        // Check 3: Verify extension matches allowed types
        $extension         = mb_strtolower($file->getClientOriginalExtension());
        $allowedExtensions = config('app.upload_photo_validation.extensions');

        if (! in_array($extension, $allowedExtensions)) {
            Log::warning('Invalid file extension', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'extension' => $extension,
            ]);

            return false;
        }

        return true;
    }
}
