<?php

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PhotoForm;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use Usernotnull\Toast\Concerns\WireToast;

class Photo extends Component
{
    use WireToast;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public PhotoForm $photoForm;

    // -----------------------------------------------------------------------
    public function mount()
    {
        $this->photoForm->photo = null;
    }

    public function savePhoto()
    {
        if ($this->isDirty()) {
            $this->photoForm->validate();

            if ($this->photoForm->image) {
                $files = File::glob(public_path() . "/storage/photos/{$this->person->id}_*.webp");
                $last_index = substr(last($files), strpos(last($files), '_') + 1, strrpos(last($files), '_') - strpos(last($files), '_') - 1);
                $next_index = str_pad(intval($last_index) + 1, 3, '0', STR_PAD_LEFT);

                // upload (new) photo
                $image_width = env('IMAGE_UPLOAD_MAX_WIDTH', 600);
                $image_height = env('IMAGE_UPLOAD_MAX_HEIGHT', 800);
                $image_quality = env('IMAGE_UPLOAD_QUALITY', 80);
                $image_type = env('IMAGE_UPLOAD_TYPE', 'webp');
                $image_name = $this->person->id . '_' . $next_index . '_' . now()->format('YmdHis') . '.' . $image_type;

                // resize (new) photo, add watermark and save it
                $manager = new ImageManager(new Driver());
                $new_image = $manager->read($this->photoForm->image)
                    ->resizeDown(width: $image_width, height: $image_height)
                    ->place(public_path('img/watermark.png'), 'bottom-left', 5, 5)
                    ->toWebp(quality: $image_quality);

                if ($new_image) {
                    $new_image->save(public_path('storage/photos/' . $image_name));

                    if ($this->person->photo == null) {
                        $this->person->update(['photo' => $image_name]);
                    }

                    // reset photo upload input
                    $this->photoForm->image = null;
                    $this->photoForm->iteration++;
                } else {
                    toast()->danger(__('app.image_not_saved') . '.', __('app.save'))->pushOnNextPage();
                }
            }

            toast()->success(__('app.created') . '.', __('app.save'))->pushOnNextPage();
            $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetPhoto()
    {
        $this->mount();

        $this->photoForm->image = null;
    }

    public function isDirty()
    {
        return $this->photoForm->image != null;
    }

    public function render()
    {
        return view('livewire.people.add.photo');
    }
}
