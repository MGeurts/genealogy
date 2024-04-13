<?php

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\ChildForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Child extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public ChildForm $childForm;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->childForm->firstname = null;
        $this->childForm->surname = null;
        $this->childForm->sex = null;
        $this->childForm->gender_id = null;

        $this->childForm->photo = null;
        $this->childForm->image = null;

        $this->childForm->person_id = null;
    }

    public function saveChild()
    {
        if ($this->isDirty()) {
            $validated = $this->childForm->validate();

            if ($validated['person_id']) {
                if ($this->person->sex === 'm') {
                    Person::findOrFail($validated['person_id'])->update([
                        'father_id' => $this->person->id,
                    ]);

                    $this->toast()->success(__('app.save'), $this->person->name . ' ' . __('app.saved') . '.')->flash()->send();
                } else {
                    Person::findOrFail($validated['person_id'])->update([
                        'mother_id' => $this->person->id,
                    ]);

                    $this->toast()->success(__('app.save'), $this->person->name . ' ' . __('app.saved') . '.')->flash()->send();
                }
            } else {
                if ($this->person->sex === 'm') {
                    $new_person = Person::create([
                        'firstname' => $validated['firstname'],
                        'surname' => $validated['surname'],
                        'sex' => $validated['sex'],
                        'gender_id' => $validated['gender_id'] ?? null,
                        'father_id' => $this->person->id,
                        'team_id' => $this->person->team_id,
                    ]);
                } else {
                    $new_person = Person::create([
                        'firstname' => $validated['firstname'],
                        'surname' => $validated['surname'],
                        'sex' => $validated['sex'],
                        'gender_id' => $validated['gender_id'] ?? null,
                        'mother_id' => $this->person->id,
                        'team_id' => $this->person->team_id,
                    ]);
                }

                if ($this->childForm->image) {
                    // if needed, create team photo folder
                    $path = storage_path('app/public/photos/' . $new_person->team_id);

                    if (! File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }

                    // upload (new) photo
                    $image_width = intval(env('IMAGE_UPLOAD_MAX_WIDTH', 600));
                    $image_height = intval(env('IMAGE_UPLOAD_MAX_HEIGHT', 800));
                    $image_quality = intval(env('IMAGE_UPLOAD_QUALITY', 80));
                    $image_type = env('IMAGE_UPLOAD_TYPE', 'webp');
                    $image_name = $new_person->team_id . '/' . $new_person->id . '_001_' . now()->format('YmdHis') . '.' . $image_type;

                    // resize (new) photo, add watermark and save it
                    $manager = new ImageManager(new Driver());
                    $new_image = $manager->read($this->childForm->image)
                        ->scaleDown(width: $image_width, height: $image_height)
                        ->place(public_path('img/watermark.png'), 'bottom-left', 5, 5)
                        ->toWebp(quality: $image_quality);

                    if ($new_image) {
                        $new_image->save(storage_path('app/public/photos/' . $image_name));

                        $new_person->update(['photo' => $image_name]);

                        // reset photo upload input
                        $this->childForm->image = null;
                        $this->childForm->iteration++;
                    } else {
                        $this->toast()->error(__('app.save'), __('app.image_not_saved') . '.')->flash()->send();
                    }
                }

                $this->toast()->success(__('app.create'), $new_person->name . ' ' . __('app.created') . '.')->flash()->send();
            }

            return $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetChild(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
        $this->childForm->firstname or
        $this->childForm->surname or
        $this->childForm->sex or
        $this->childForm->gender_id or

        $this->childForm->image != null or

        $this->childForm->person_id;
    }

    // -----------------------------------------------------------------------
    public function render()
    {
        if ($this->person->sex === 'm') {
            $persons = Person::where('id', '!=', $this->person->id)
                ->whereNull('father_id')
                ->YoungerThan($this->person->birth_date, $this->person->birth_year)
                ->orderBy('firstname')->orderBy('surname')
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name . ' [' . strtoupper($p->sex) . '] ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
                    ];
                })->toArray();
        } else {
            $persons = Person::where('id', '!=', $this->person->id)
                ->whereNull('mother_id')
                ->YoungerThan($this->person->birth_date, $this->person->birth_year)
                ->orderBy('firstname')->orderBy('surname')
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name . ' [' . strtoupper($p->sex) . '] ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
                    ];
                })->toArray();
        }

        return view('livewire.people.add.child')->with(compact('persons'));
    }
}
