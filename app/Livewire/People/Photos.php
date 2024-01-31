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

    public $maxImages = 5;      // default

    protected $listeners = [
        'photo_updated' => 'mount',
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

        $files = File::glob(public_path() . '/storage/photos/' . $this->person->team_id  . '/' . $this->person->id . '_*.webp');

        $this->person->update([
            'photo' => $files ? $this->person->team_id . '/' . substr($files[0], strrpos($files[0], '/') + 1) : null,
        ]);

        $this->dispatch('photo_updated');
    }

    public function mount()
    {
        $this->images = [];
        $this->selected = 0;

        $files = File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp');

        foreach ($files as $file) {
            if (count($this->images) < $this->maxImages) {
                array_push($this->images, $this->person->team_id . '/' . substr($file, strrpos($file, '/') + 1));
            }
        }
    }

    public function render()
    {
        return view('livewire.people.photos');
    }
}
