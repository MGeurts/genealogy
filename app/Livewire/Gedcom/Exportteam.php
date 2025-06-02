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

    public $user;

    public array $formats = [];

    public array $encodings = [];

    public string $filename;

    public string $format = 'gedcom';

    public string $encoding = 'utf8';

    public string $line_endings = 'windows';

    public Collection $teamPersons;

    public Collection $teamCouples;

    public function mount(): void
    {
        $this->user = Auth()->user();

        $this->teamPersons = $this->user->currentTeam->persons->sortBy('name')->values();
        $this->teamCouples = $this->user->currentTeam->couples->sortBy('name')->values();

        $this->formats = [
            ['value' => 'gedcom', 'label' => 'GEDCOM'],
            ['value' => 'zip', 'label' => 'ZIP'],
            ['value' => 'zipmedia', 'label' => 'ZIP ' . __('gedcom.includes_media')],
            ['value' => 'gedzip', 'label' => 'GEDZIP ' . __('gedcom.includes_media')],
        ];

        $this->encodings = [
            ['value' => 'utf8', 'label' => 'UTF-8'],
            ['value' => 'unicode', 'label' => 'UNICODE (UTF16-BE)'],
            ['value' => 'ansel', 'label' => 'ANSEL'],
            ['value' => 'ascii', 'label' => 'ASCII'],
            ['value' => 'ansi', 'label' => 'ANSI (CP1252)'],

        ];

        $this->filename = Str::slug(($this->user->currentTeam->name) . '-' . now()->format('Y-m-d-H-i-s'));
    }

    public function exportteam(): StreamedResponse
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
        return view('livewire.gedcom.exportteam');
    }
}
