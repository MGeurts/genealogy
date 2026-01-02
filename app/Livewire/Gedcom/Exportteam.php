<?php

declare(strict_types=1);

namespace App\Livewire\Gedcom;

use App\Gedcom\Export\Export;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TallStackUi\Traits\Interactions;

final class Exportteam extends Component
{
    use Interactions;

    private const MAX_FILENAME_LENGTH = 100;

    public User $user;

    public string $filename;

    public string $format = 'gedcom';

    public string $teamname;

    /** @var Collection<int, \App\Models\Person> */
    public Collection $teamPersons;

    /** @var Collection<int, \App\Models\Couple> */
    public Collection $teamCouples;

    /**
     * @return array<int, array{value: string, label: string, zip: bool, description: string}>
     */
    public static function formats(): array
    {
        return [
            ['value' => 'gedcom',   'label' => 'GEDCOM',                'zip' => false, 'description' => __('gedcom.export_description_gedcom')],
            ['value' => 'zip',      'label' => 'ZIP',                   'zip' => true,  'description' => __('gedcom.export_description_zip')],
            ['value' => 'zipmedia', 'label' => 'ZIP Includes Media',    'zip' => true,  'description' => __('gedcom.export_description_zipmedia')],
            ['value' => 'gedzip',   'label' => 'GEDZIP Includes Media', 'zip' => true,  'description' => __('gedcom.export_description_gedzip')],
        ];
    }

    public function mount(): void
    {
        $this->user = auth()->user();

        $this->teamname = $this->user->currentTeam->name;

        // Load team data with relationships for better performance
        $this->teamPersons = $this->user->currentTeam
            ->persons()
            ->with(['metadata'])
            ->orderBy('surname')
            ->orderBy('firstname')
            ->get();

        $this->teamCouples = $this->user->currentTeam
            ->couples()
            ->with(['person1', 'person2'])
            ->get();

        $this->filename = $this->generateFilename();
    }

    public function exportTeam(): ?StreamedResponse
    {
        $this->validate();

        try {
            $export = new Export($this->filename, $this->format, $this->teamname);

            $gedcom = $export->export(
                individuals: $this->teamPersons,
                couples: $this->teamCouples,
            );

            $this->toast()->success(__('app.download'), __('gedcom.export_succeeded') . ': <br/>' . __('app.downloading'))->send();

            // Use FORMATS zip flag to decide download type
            $formatConfig = collect(self::formats())->firstWhere('value', $this->format);

            if ($formatConfig['zip'] ?? false) {
                return $export->downloadZip($gedcom);
            }

            return $export->downloadGedcom($gedcom);
        } catch (Exception $e) {
            logger()->error(__('gedcom.export_failed'), [
                'user_id'           => $this->user->id,
                'team_id'           => $this->user->currentTeam->id,
                'format'            => $this->format,
                'filename'          => $this->filename,
                'error'             => $e->getMessage(),
                'individuals_count' => $this->teamPersons->count(),
                'couples_count'     => $this->teamCouples->count(),
            ]);

            $this->toast()->error(__('app.error'), __('gedcom.export_failed') . ': ' . $e->getMessage())->send();

            return null;
        }
    }

    public function updatedFilename(): void
    {
        // Regenerate filename when manually changed
        $this->filename = $this->sanitizeFilename($this->filename);
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.gedcom.exportteam', ['formats' => self::formats()]);
    }

    // ------------------------------------------------------------------------------
    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'filename' => ['required', 'string'],
            'format'   => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'filename' => __('gedcom.filename'),
            'format'   => __('gedcom.format'),
        ];
    }

    // ----------------------------------------------------------------------
    /**
     * Generate a clean, timezone-aware, UTC-safe filename.
     * Always keeps the timestamp + UTC offset intact,
     * truncating only the team name part if needed.
     */
    private function generateFilename(?string $teamName = null, ?Carbon $dateTime = null): string
    {
        $tz  = $this->user->timezone ?? config('app.timezone', 'UTC');
        $now = $dateTime ?? now($tz);

        $teamName = $teamName ?? $this->user->currentTeam->name;

        // Slugify only the team name, if needed
        $safeTeam = $this->sanitizeTeamName($teamName);

        // Fixed suffix (always preserved)
        $suffix = '-' . $now->format('Y-m-d-H-i-s') . '-utc' . $now->format('O');

        // Ensure suffix always fits in max length
        $available = max(self::MAX_FILENAME_LENGTH - mb_strlen($suffix), 1);

        if (mb_strlen($safeTeam) > $available) {
            $safeTeam = mb_substr($safeTeam, 0, $available);
        }

        return $safeTeam . $suffix;
    }

    /**
     * Sanitize team name for filename use
     */
    private function sanitizeTeamName(string $teamName): string
    {
        // Remove or replace problematic characters
        $safe = preg_replace('/[^\p{L}\p{N}\-\+\s]/u', '', $teamName);

        // Use slug if original contains non-ASCII characters
        if (preg_match('/[^\x00-\x7F]/', $safe)) {
            return Str::slug($safe);
        }

        // Lower case, replace spaces with hyphens and clean up
        return mb_strtolower(preg_replace('/\s+/', '-', mb_trim($safe)));
    }

    /**
     * Sanitize user-provided filename
     */
    private function sanitizeFilename(string $filename): string
    {
        // Remove any file extensions
        $filename = preg_replace('/\.(ged|gedcom|zip|gdz)$/i', '', $filename);

        // Keep only allowed characters
        $sanitized = preg_replace('/[^a-z0-9\-\+\_\.]/i', '', mb_trim($filename));

        // Truncate if too long
        return mb_substr($sanitized, 0, self::MAX_FILENAME_LENGTH);
    }
}
