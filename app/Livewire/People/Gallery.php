<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class Gallery extends Component
{
    public $person;

    public $images = null;

    public $selected = null;

    // -----------------------------------------------------------------------
    protected $listeners = [
        'photos_updated' => 'mount',
        'person_updated' => 'render',
    ];

    // -----------------------------------------------------------------------
    public function mount()
    {
        $this->images = collect(File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp'))
            ->map(function ($p) {
                return substr($p, strrpos($p, '/') + 1);
            })->toArray();

        if (count($this->images) > 0) {
            if ($this->person->photo) {
                $this->selected = array_search($this->person->photo, $this->images);
            } else {
                $this->selected = 0;
            }
        }
    }

    public function previousImage()
    {
        $this->selected--;

        if ($this->selected < 0) {
            $this->selected = count($this->images) - 1;
        }
    }

    public function nextImage()
    {
        $this->selected++;

        if ($this->selected == count($this->images)) {
            $this->selected = 0;
        }
    }

    public function selectImage($index)
    {
        $this->selected = $index;
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.gallery');
    }
}
