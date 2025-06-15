<?php

declare(strict_types=1);

namespace App\Livewire\Gedcom;

use App\Php\Gedcom\Export;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TallStackUi\Traits\Interactions;

final class Exportteam extends Component
{
    use Interactions;

    public const FORMATS = [
        ['value' => 'gedcom', 'label' => 'GEDCOM'],
        ['value' => 'zip', 'label' => 'ZIP'],
        ['value' => 'zipmedia', 'label' => 'ZIP Includes Media'],
        ['value' => 'gedzip', 'label' => 'GEDZIP Includes Media'],
    ];

    public const ENCODINGS = [
        ['value' => 'utf8', 'label' => 'UTF-8'],
        ['value' => 'unicode', 'label' => 'UNICODE (UTF16-BE)'],
        ['value' => 'ansel', 'label' => 'ANSEL'],
        ['value' => 'ascii', 'label' => 'ASCII'],
        ['value' => 'ansi', 'label' => 'ANSI (CP1252)'],
    ];

    public $user;

    public string $filename;

    public string $format = 'gedcom';

    public string $encoding = 'utf8';

    public string $line_endings = 'windows';

    public Collection $teamPersons;

    public Collection $teamCouples;

    public function mount(): void
    {
        $this->user = auth()->user();

        $this->teamPersons = $this->user->currentTeam->persons->sortBy('name')->values();
        $this->teamCouples = $this->user->currentTeam->couples->sortBy('name')->values();

        $this->filename = Str::slug(($this->user->currentTeam->name) . '-' . now()->format('Y-m-d-H-i-s'));
    }

    public function exportTeam(): StreamedResponse
    {
        $this->validate();

        $export = new Export(
            $this->filename,
            $this->format,
            $this->encoding,
            $this->line_endings
        );

        $gedcom = $export->Export(
            individuals: $this->teamPersons,
            families: $this->teamCouples,
        );

        $this->toast()->success(__('app.download'), __('app.downloading'))->send();

        if (in_array($this->format, ['zip', 'zipmedia', 'gedzip'])) {
            return $export->downloadZip($gedcom);
        }

        return $export->downloadGedcom($gedcom);
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.gedcom.exportteam', [
            'formats'   => self::FORMATS,
            'encodings' => self::ENCODINGS,
        ]);
    }

    // ------------------------------------------------------------------------------
    protected function rules(): array
    {
        return [
            'filename'     => ['required', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/i'],
            'format'       => ['required', 'string', 'in:' . collect(self::FORMATS)->pluck('value')->implode(',')],
            'encoding'     => ['required', 'string', 'in:' . collect(self::ENCODINGS)->pluck('value')->implode(',')],
            'line_endings' => ['required', 'string', 'in:windows,unix,mac'],
        ];
    }

    protected function messages(): array
    {
        return [];
    }

    protected function validationAttributes(): array
    {
        return [
            'filename'     => __('gedcom.filename'),
            'format'       => __('gedcom.format'),
            'encoding'     => __('gedcom.character_encoding'),
            'line_endings' => __('gedcom.line_endings'),
        ];
    }
}
