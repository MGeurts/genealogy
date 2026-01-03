<?php

declare(strict_types=1);

namespace App\Livewire\Gedcom;

use App\Gedcom\Import\Import;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\User;
use Exception;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Importteam extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public User $user;

    public ?string $name = null;

    public ?string $description = null;

    public ?TemporaryUploadedFile $file = null;

    /** @var array{success: bool, individuals_imported?: int, families_imported?: int, team?: string, error?: string}|null */
    public ?array $result = null;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->user = auth()->user();
    }

    public function importteam(): void
    {
        $this->validate();

        try {
            $importer = new Import(
                $this->name,
                $this->description,
            );

            // Check file type
            $extension = mb_strtolower($this->file->getClientOriginalExtension());

            if (in_array($extension, ['zip'])) {
                // Import from ZIP (with media)
                $tempPath = $this->file->getRealPath();

                $this->result = $importer->importFromZip($tempPath);
            } else {
                // Import from GEDCOM text
                $content = file_get_contents($this->file->getRealPath());

                // Handle file_get_contents failure
                if ($content === false) {
                    throw new Exception('Failed to read file contents.');
                }

                // Scan for potentially malicious content
                if ($this->containsMaliciousContent($content)) {
                    throw new Exception('File contains potentially dangerous content.');
                }

                $this->result = $importer->import($content);
            }

            if ($this->result['success']) {
                // Safely access array offsets with null coalescing
                $individualsImported = $this->result['individuals_imported'] ?? 0;
                $familiesImported    = $this->result['families_imported'] ?? 0;
                $teamName            = $this->result['team'] ?? 'team';

                $this->toast()->success('Success', "Imported {$individualsImported} individuals and {$familiesImported} families into {$teamName}.")->send();

                $this->redirect('/search');
            } else {
                $errorMessage = $this->result['error'] ?? 'Unknown error occurred';
                $this->toast()->error('Error', $errorMessage)->send();
            }
        } catch (Exception $e) {
            $this->toast()->error('Import Error', $e->getMessage())->send();

            // Log detailed error for debugging
            logger()->error('GEDCOM Import failed', [
                'user_id'  => $this->user->id,
                'filename' => $this->file?->getClientOriginalName(),
                'filesize' => $this->file?->getSize(),
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);
        }
    }

    // -----------------------------------------------------------------------

    public function render(): View
    {
        return view('livewire.gedcom.importteam');
    }

    // -----------------------------------------------------------------------
    /**
     * @return array<string, array<int, string|int>>
     */
    protected function rules(): array
    {
        return $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'file'        => ['required', 'file', 'mimes:ged,zip', 'max:51200'],  // 50MB max
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'file.required' => ('validation.required'),
            'file.file'     => ('validation.required'),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'name'        => __('team.name'),
            'description' => __('team.description'),
            'file'        => __('gedcom.gedcom_file'),
        ];
    }

    /**
     * Scan for potentially malicious content
     */
    private function containsMaliciousContent(string $content): bool
    {
        // Check for common script injection patterns
        $maliciousPatterns = [
            '/<\?php/i',
            '/<script/i',
            '/javascript:/i',
            '/data:text\/html/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/eval\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        // Check for suspiciously long lines (potential buffer overflow attempts)
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (mb_strlen($line) > 10000) { // 10KB per line is excessive for GEDCOM
                return true;
            }
        }

        return false;
    }
}
