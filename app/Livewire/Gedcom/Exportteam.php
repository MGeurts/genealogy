<?php

declare(strict_types=1);

namespace App\Livewire\Gedcom;

use App\Php\Gedcom\Export;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Exportteam extends Component
{
    use Interactions;

    public $user;

    public Collection $teamPersons;

    public string $filename;

    public string $format = 'gedcom';

    public array $formats = [];

    public string $encoding = 'utf8';

    public array $encodings = [];

    public string $line_endings = 'windows';

    public function mount(): void
    {
        $this->user = Auth()->user();

        $this->teamPersons = Auth::user()->currentTeam->persons->sortBy('name')->values();

        $this->formats = [
            ['value' => 'gedcom', 'label' => 'GEDCOM', 'extension' => '.ged'],
            ['value' => 'zip', 'label' => 'ZIP', 'extension' => '.zip'],
            ['value' => 'zipmedia', 'label' => 'ZIP ' . __('gedcom.includes_media'), 'extension' => '.zip'],
            ['value' => 'gedzip', 'label' => 'GEDZIP ' . __('gedcom.includes_media'), 'extension' => '.gdz'],
        ];

        $this->encodings = [
            ['value' => 'utf8', 'label' => 'UTF-8'],
            ['value' => 'unicode', 'label' => 'UNICODE (UTF16-BE)'],
            ['value' => 'ansel', 'label' => 'ANSEL'],
            ['value' => 'ascii', 'label' => 'ASCII'],
            ['value' => 'ansi', 'label' => 'ANSI (CP1252)'],

        ];

        $this->setFilename();
    }

    public function updatedFormat(): void
    {
        $this->setFilename();
    }

    public function exportteam(): void
    {
        $export = new Export(
            $this->filename,
            $this->format,
            $this->encoding,
            $this->line_endings
        );

        $export->Export();

        $this->toast()->success(__('app.download'), mb_strtoupper(__('app.under_construction')))->send();
    }

    // -----------------------------------------------------------------------

    public function render(): View
    {
        return view('livewire.gedcom.exportteam');
    }

    // -----------------------------------------------------------------------
    private function setFilename(): void
    {
        $this->filename = $this->cleanString(Auth()->user()->currentTeam->name) . '-' . now()->format('Y-m-d-H-i-s') . $this->getExtension();
    }

    private function getExtension(): string
    {
        return collect($this->formats)->where('value', $this->format)->first()['extension'];
    }

    private function cleanString(string $string): string
    {
        $string = str_replace(' ', '-', $string);                      // Replaces all spaces with hyphens
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);  // Removes special chars
        $string = preg_replace('/-+/', '-', (string) $string);             // Replaces multiple hyphens with single one

        return mb_strtolower((string) $string);
    }
}
