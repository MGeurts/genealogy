<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Models\Person;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use TallStackUi\Traits\Interactions;
use ZipArchive;

/**
 * File management component for Person model.
 *
 * Uses Livewire 4's reactive properties to eliminate unnecessary database queries.
 * The $files collection is updated in-memory after mutations (save/delete/move),
 * allowing the UI to reactively update without page reloads or re-queries.
 */
final class Files extends Component
{
    use Interactions;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public Person $person;

    public ?string $source = null;

    public ?string $source_date = null;

    /** @var array<int, UploadedFile> */
    public array $uploads = [];

    /** @var array<int, UploadedFile> */
    public array $backup = [];

    // Cache config values as locked properties
    #[Locked]
    public string $acceptedFormats = '';

    #[Locked]
    public string $acceptMimes = '';

    #[Locked]
    public int $maxSize = 0;

    /** @var Collection<int, Media>|null */
    public ?Collection $files = null;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        // Cache config values once during mount
        $acceptConfig          = config('app.upload_file_accept');
        $this->acceptedFormats = implode(', ', array_values($acceptConfig));
        $this->acceptMimes     = implode(',', array_keys($acceptConfig));
        $this->maxSize         = config('app.upload_max_size');

        $this->loadFiles();
    }

    /**
     * Handle file deletion from uploads.
     *
     * @param  array{temporary_name: string, real_name: string, extension: string, size: int, path: string, url: string}  $content
     */
    public function deleteUpload(array $content): void
    {
        if (empty($this->uploads)) {
            return;
        }

        $this->uploads = collect($this->uploads)
            ->filter(fn (UploadedFile $file): bool => $file->getFilename() !== $content['temporary_name'])
            ->values()
            ->toArray();

        rescue(
            fn () => File::delete(storage_path('app/livewire-tmp/' . $content['temporary_name'])),
            report: false
        );
    }

    /**
     * Handle updates to the uploads property.
     */
    public function updatingUploads(): void
    {
        $this->backup = $this->uploads;
    }

    /**
     * Process uploaded files and remove duplicates.
     * Validation is deferred to save() for better performance.
     */
    public function updatedUploads(): void
    {
        if (empty($this->uploads)) {
            return;
        }

        // Merge and deduplicate only - validation happens at save time
        $this->uploads = collect(array_merge($this->backup, (array) $this->uploads))
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->values()
            ->toArray();

        $this->backup = [];
    }

    /**
     * Save uploaded files.
     */
    public function save(): void
    {
        $this->validate();

        if (empty($this->uploads)) {
            return;
        }

        // Double-check validation before saving
        $validUploads = collect($this->uploads)
            ->filter(fn (UploadedFile $file): bool => $this->isValidFile($file))
            ->values()
            ->toArray();

        if (empty($validUploads)) {
            $this->toast()->warning(
                __('app.warning'),
                __('person.no_valid_files_to_save')
            )->send();

            return;
        }

        $savedCount = 0;
        $newFiles   = [];

        foreach ($validUploads as $upload) {
            try {
                $file = $this->person->addMedia($upload)->toMediaCollection('files', 'files');

                if (isset($this->source)) {
                    $file->setCustomProperty('source', $this->source);
                }

                if (isset($this->source_date)) {
                    $file->setCustomProperty('source_date', $this->source_date);
                }

                $file->save();
                $newFiles[] = $file;
                $savedCount++;
            } catch (Exception $e) {
                Log::error('Failed to save file', [
                    'person_id' => $this->person->id,
                    'filename'  => $upload->getClientOriginalName(),
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        if ($savedCount > 0) {
            $this->toast()->success(
                __('app.save'),
                trans_choice('person.files_saved', $savedCount)
            )->send();

            // Show warning if some files were invalid
            $invalidCount = count($this->uploads) - count($validUploads);
            if ($invalidCount > 0) {
                $this->toast()->warning(
                    __('app.warning'),
                    trans_choice('person.files_invalid', $invalidCount)
                )->send();
            }

            // Add new files to the collection in memory
            if ($this->files) {
                $this->files = $this->files->concat($newFiles)->sortBy('order_column')->values();
            } else {
                $this->files = collect($newFiles)->sortBy('order_column')->values();
            }

            // Reset form fields
            $this->uploads     = [];
            $this->source      = null;
            $this->source_date = null;
        } else {
            $this->toast()->error(
                __('app.error'),
                __('person.files_save_failed')
            )->send();
        }
    }

    /**
     * Delete a file.
     */
    public function deleteFile(int $id): void
    {
        if (! $this->files) {
            return;
        }

        $file = $this->files->firstWhere('id', $id);

        if ($file) {
            $file->delete();

            // Remove from collection and reorder in memory
            $this->files = $this->files->reject(fn ($item) => $item->id === $id)
                ->values()
                ->map(function ($item, $index) {
                    $item->order_column = $index + 1;
                    $item->save();

                    return $item;
                });

            $this->toast()->success(__('app.delete'), __('person.file_deleted'))->send();
        }
    }

    /**
     * Move a file up or down the sorted list.
     */
    public function moveFile(int $position, string $direction): void
    {
        if (! $this->files) {
            return;
        }

        $targetPosition = $direction === 'up' ? $position - 1 : $position + 1;

        // Update the collection in memory
        $this->files = $this->files->map(function ($file) use ($position, $targetPosition) {
            if ($file->order_column === $position) {
                $file->order_column = $targetPosition;
                $file->save();
            } elseif ($file->order_column === $targetPosition) {
                $file->order_column = $position;
                $file->save();
            }

            return $file;
        })->sortBy('order_column')->values();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.files');
    }

    // ------------------------------------------------------------------------------
    /**
     * @return array<string, array<int, string|int>>
     */
    protected function rules(): array
    {
        return [
            'uploads.*' => [
                'required',
                'file',
                'mimes:' . config('app.upload_file_validation.mimes_rule'),
                'max:' . config('app.upload_max_size'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        $acceptedFormats = implode(', ', array_values(config('app.upload_file_accept')));

        return [
            'uploads.*.required' => __('validation.required', ['attribute' => __('person.files')]),
            'uploads.*.file'     => __('validation.file', ['attribute' => __('person.files')]),
            'uploads.*.mimes'    => __('validation.mimes', [
                'attribute' => __('person.files'),
                'values'    => $acceptedFormats,
            ]),
            'uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.files'),
                'max'       => config('app.upload_max_size'),
            ]),
        ];
    }

    // -----------------------------------------------------------------------
    /**
     * Validate that uploaded file is genuine and safe.
     * Performs multiple security checks to prevent malicious uploads.
     *
     * @param  UploadedFile  $file  The file to validate
     * @return bool True if file is valid, false otherwise
     */
    private function isValidFile(UploadedFile $file): bool
    {
        // Check 1: Verify MIME type matches config
        $mimeType     = $file->getMimeType();
        $allowedMimes = array_keys(config('app.upload_file_accept'));

        if (! in_array($mimeType, $allowedMimes)) {
            Log::warning('Invalid MIME type detected in file upload', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'mime'      => $mimeType,
                'allowed'   => $allowedMimes,
            ]);

            return false;
        }

        // Check 2: Verify extension matches allowed types
        $extension         = mb_strtolower($file->getClientOriginalExtension());
        $allowedExtensions = config('app.upload_file_validation.extensions');

        if (! in_array($extension, $allowedExtensions)) {
            Log::warning('Invalid file extension', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'extension' => $extension,
            ]);

            return false;
        }

        // Check 3: Verify file is not executable
        $dangerousExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'php8', 'phar', 'exe', 'sh', 'bat', 'cmd', 'com', 'scr', 'vbs', 'js', 'jar'];

        if (in_array($extension, $dangerousExtensions)) {
            Log::warning('Dangerous file extension detected', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'extension' => $extension,
            ]);

            return false;
        }

        // Check 4: Verify file signature (magic bytes) matches extension
        if (! $this->verifyFileSignature($file, $extension)) {
            Log::warning('File signature mismatch', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'extension' => $extension,
            ]);

            return false;
        }

        // Check 5: Additional validation for specific file types
        if (! $this->validateFileContent($file, $extension)) {
            Log::warning('File content validation failed', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'extension' => $extension,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Verify file signature (magic bytes) matches the expected type.
     */
    private function verifyFileSignature(UploadedFile $file, string $extension): bool
    {
        $handle = fopen($file->getRealPath(), 'rb');
        if (! $handle) {
            return false;
        }

        $bytes = fread($handle, 8);
        fclose($handle);

        if ($bytes === false) {
            return false;
        }

        // Convert to hex for comparison
        $hex = bin2hex($bytes);

        // File signatures (magic bytes)
        $signatures = [
            'pdf'  => ['25504446'],                                    // %PDF
            'txt'  => [],                                              // No specific signature
            'doc'  => ['d0cf11e0a1b11ae1'],                           // DOC (OLE)
            'docx' => ['504b0304', '504b0506', '504b0708'],           // DOCX (ZIP)
            'xls'  => ['d0cf11e0a1b11ae1'],                           // XLS (OLE)
            'xlsx' => ['504b0304', '504b0506', '504b0708'],           // XLSX (ZIP)
            'odt'  => ['504b0304', '504b0506', '504b0708'],           // ODT (ZIP)
        ];

        // TXT files don't have a specific signature, so skip check
        if ($extension === 'txt') {
            return true;
        }

        if (! isset($signatures[$extension])) {
            return false;
        }

        foreach ($signatures[$extension] as $signature) {
            if (str_starts_with($hex, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate file content for specific file types.
     */
    private function validateFileContent(UploadedFile $file, string $extension): bool
    {
        // PDF validation
        if ($extension === 'pdf') {
            return $this->validatePdf($file);
        }

        // Office document validation (DOCX, XLSX, ODT are ZIP-based)
        if (in_array($extension, ['docx', 'xlsx', 'odt'])) {
            return $this->validateZipBasedDocument($file);
        }

        return true;
    }

    /**
     * Validate PDF file.
     */
    private function validatePdf(UploadedFile $file): bool
    {
        try {
            // Read first 1024 bytes
            $handle = fopen($file->getRealPath(), 'rb');
            if (! $handle) {
                return false;
            }

            $header = fread($handle, 1024);
            fclose($handle);

            // PHPStan fix: fread can return false
            if ($header === false) {
                return false;
            }

            // Check for PDF header
            if (! str_starts_with($header, '%PDF-')) {
                return false;
            }

            // Check for dangerous JavaScript or actions
            $dangerousPatterns = [
                '/\/JavaScript/i',
                '/\/JS/i',
                '/\/Launch/i',
                '/\/ImportData/i',
            ];

            foreach ($dangerousPatterns as $pattern) {
                if (preg_match($pattern, $header)) {
                    Log::warning('Potentially dangerous PDF content detected', [
                        'person_id' => $this->person->id,
                        'file'      => $file->getClientOriginalName(),
                    ]);
                    // Note: Don't auto-reject, just log. Many legitimate PDFs have JavaScript
                }
            }

            return true;
        } catch (Exception $e) {
            Log::error('PDF validation error', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'error'     => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Validate ZIP-based document (DOCX, XLSX, ODT).
     */
    private function validateZipBasedDocument(UploadedFile $file): bool
    {
        try {
            $zip    = new ZipArchive();
            $result = $zip->open($file->getRealPath());

            if ($result !== true) {
                return false;
            }

            // Check for suspicious files in the archive
            $dangerousPatterns = [
                '/\.exe$/i',
                '/\.dll$/i',
                '/\.sh$/i',
                '/\.bat$/i',
                '/\.vbs$/i',
                '/\.php$/i',
            ];

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);

                if ($filename === false) {
                    continue;
                }

                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $filename) === 1) {
                        $zip->close();

                        Log::warning('Dangerous file found in document archive', [
                            'person_id'       => $this->person->id,
                            'file'            => $file->getClientOriginalName(),
                            'suspicious_file' => $filename,
                        ]);

                        return false;
                    }
                }
            }

            $zip->close();

            return true;
        } catch (Exception $e) {
            Log::error('Document validation error', [
                'person_id' => $this->person->id,
                'file'      => $file->getClientOriginalName(),
                'error'     => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function loadFiles(): void
    {
        $this->files = $this->person->getMedia('files');
    }
}
