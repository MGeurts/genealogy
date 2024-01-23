<?php

namespace App\Livewire\People;

use Illuminate\Support\Facades\File;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Photos extends Component
{
    use WireToast;

    public $person;

    public $images = [];

    public $selected = 0;

    public $maxImages = 5;

    protected $listeners = [
        'person_updated' => 'render',
    ];

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

    public function deleteImage()
    {
        File::delete(File::glob(storage_path('app/public/*/' . $this->images[$this->selected])));

        toast()->success(__('person.photo_deleted') . '.', __('app.delete'))->push();

        $this->mount();
        $this->dispatch('person_updated');
    }

    public function mount()
    {
        $this->images = [];
        $this->selected = 0;

        $files = File::glob(public_path() . "/storage/photos/{$this->person->id}_*.webp");

        foreach ($files as $file) {
            if (count($this->images) < $this->maxImages) {
                array_push($this->images, substr($file, strrpos($file, '/') + 1));
            }
        }
    }

    public function render()
    {
        return view('livewire.people.photos');
    }
}
