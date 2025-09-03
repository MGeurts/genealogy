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

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->user = auth()->user();
    }

    public function importteam(): void
    {
        $this->validate();

        try {
            $content = $this->file->get();

            // Scan for potentially malicious content
            if ($this->containsMaliciousContent($content)) {
                throw new Exception('File contains potentially dangerous content.');
            }

            $importer = new Import(
                $this->name,
                $this->description,
            );

            $result = $importer->import($content);

            if ($result['success']) {
                $this->toast()->success('Success', "Imported {$result['individuals_imported']} individuals and {$result['families_imported']} families into {$result['team']}.")->send();

                $this->redirect('/search');
            } else {
                $this->toast()->error('Error', $result['error'])->send();
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
    protected function rules(): array
    {
        return $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'file'        => ['required', 'file'],
        ];
    }

    protected function messages(): array
    {
        return [
            'file.required' => ('validation.required'),
            'file.file'     => ('validation.required'),
        ];
    }

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
