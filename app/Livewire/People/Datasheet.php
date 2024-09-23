<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;

class Datasheet extends Component
{
    // ------------------------------------------------------------------------------
    public $person;

    public array $images = [];

    public Collection $files;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->images = collect(File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp'))
            ->map(function ($p) {
                return substr($p, strrpos($p, '/') + 1);
            })->toArray();

        $this->files = $this->person->getMedia('files');
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.datasheet');
    }
}
