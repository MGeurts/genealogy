<?php

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\ProfileForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use Usernotnull\Toast\Concerns\WireToast;

class Profile extends Component
{
    use TrimStringsAndConvertEmptyStringsToNull;
    use WireToast;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public ProfileForm $profileForm;

    // -----------------------------------------------------------------------
    public function mount()
    {
        $this->profileForm->person = $this->person;

        $this->profileForm->firstname = $this->person->firstname;
        $this->profileForm->surname = $this->person->surname;
        $this->profileForm->birthname = $this->person->birthname;
        $this->profileForm->nickname = $this->person->nickname;

        $this->profileForm->sex = $this->person->sex;
        $this->profileForm->gender_id = $this->person->gender_id;

        $this->profileForm->yob = $this->person->yob ? $this->person->yob : null;
        $this->profileForm->dob = $this->person->dob?->format('Y-m-d');
        $this->profileForm->pob = $this->person->pob;

        $this->profileForm->photo = $this->person->photo;
        $this->profileForm->image = null;
    }

    public function saveProfile()
    {
        if ($this->isDirty()) {
            $validated = $this->profileForm->validate();
            if ($this->profileForm->image) {
                // upload (new) photo
                $image_width = intval(env('IMAGE_UPLOAD_MAX_WIDTH', 600));
                $image_height = intval(env('IMAGE_UPLOAD_MAX_HEIGHT', 800));
                $image_quality = intval(env('IMAGE_UPLOAD_QUALITY', 80));
                $image_type = env('IMAGE_UPLOAD_TYPE', 'webp');
                $image_name = $this->person->team_id . '/' . $this->person->id . '_001_' . now()->format('YmdHis') . '.' . $image_type;

                // delete old photos
                File::delete(File::glob(storage_path('app/public/photos/' . $this->person->team_id . '/' . $this->person->id . '_001_*.webp')));

                // resize (new) photo, watermark and save it
                $manager = new ImageManager(new Driver());
                $new_image = $manager->read($this->profileForm->image)
                    ->resizeDown(width: $image_width, height: $image_height)
                    ->place(public_path('img/watermark.png'), 'bottom-left', 5, 5)
                    ->toWebp(quality: $image_quality);

                if ($new_image) {
                    $new_image->save(storage_path('app/public/photos/' . $image_name));

                    $validated['photo'] = $image_name;

                    // reset photo upload input
                    $this->profileForm->image = null;
                    $this->profileForm->iteration++;
                } else {
                    toast()->danger(__('app.image_not_saved') . '.', __('app.save'))->pushOnNextPage();
                }
            }

            $this->person->update($validated);

            toast()->success(__('app.saved') . '.', __('app.save'))->pushOnNextPage();

            $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetProfile()
    {
        $this->mount();
    }

    public function isDirty()
    {
        return
        $this->profileForm->firstname != $this->person->firstname or
        $this->profileForm->surname != $this->person->surname or
        $this->profileForm->birthname != $this->person->birthname or
        $this->profileForm->nickname != $this->person->nickname or

        $this->profileForm->sex != $this->person->sex or
        $this->profileForm->gender_id != $this->person->gender_id or

        $this->profileForm->yob != $this->person->yob or
        $this->profileForm->dob != ($this->person->dob ? $this->person->dob->format('Y-m-d') : null) or
        $this->profileForm->pob != $this->person->pob or

        $this->profileForm->image != null;
    }
}
