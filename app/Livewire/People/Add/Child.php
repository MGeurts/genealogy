<?php

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\ChildForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
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

    public $photos = [];

    public $backup = [];

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->childForm->firstname = null;
        $this->childForm->surname   = null;
        $this->childForm->birthname = null;
        $this->childForm->nickname  = null;

        $this->childForm->sex       = null;
        $this->childForm->gender_id = null;

        $this->childForm->yob = null;
        $this->childForm->dob = null;
        $this->childForm->pob = null;

        $this->childForm->photo = null;

        $this->childForm->person_id = null;
    }

    public function deleteUpload(array $content): void
    {
        /*
        the $content contains:
        [
            'temporary_name',
            'real_name',
            'extension',
            'size',
            'path',
            'url',
        ]
        */

        if (! $this->photos) {
            return;
        }

        $files = Arr::wrap($this->photos);

        /** @var UploadedFile $file */
        $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $content['temporary_name'])->first();

        // here we delete the file.
        // even if we have a error here, we simply ignore it because as long as the file is not persisted, it is temporary and will be deleted at some point if there is a failure here
        rescue(fn () => $file->delete(), report: false);

        $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);

        // we guarantee restore of remaining files regardless of upload type, whether you are dealing with multiple or single uploads
        $this->photos = is_array($this->photos) ? $collect->toArray() : $collect->first();
    }

    public function updatingPhotos(): void
    {
        // we store the uploaded files in the temporary property
        $this->backup = $this->photos;
    }

    public function updatedPhotos(): void
    {
        if (! $this->photos) {
            return;
        }

        // we merge the newly uploaded files with the saved ones
        $file = Arr::flatten(array_merge($this->backup, [$this->photos]));

        // we finishing by removing the duplicates
        $this->photos = collect($file)->unique(fn (UploadedFile $item) => $item->getClientOriginalName())->toArray();
    }

    public function saveChild()
    {
        if ($this->isDirty()) {
            $validated = $this->childForm->validate();

            if (isset($validated['person_id'])) {
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
                        'surname'   => $validated['surname'],
                        'birthname' => $validated['birthname'],
                        'nickname'  => $validated['nickname'],
                        'sex'       => $validated['sex'],
                        'gender_id' => $validated['gender_id'] ?? null,
                        'yob'       => $validated['yob'],
                        'dob'       => $validated['dob'],
                        'pob'       => $validated['pob'],
                        'father_id' => $this->person->id,
                        'team_id'   => $this->person->team_id,
                    ]);
                } else {
                    $new_person = Person::create([
                        'firstname' => $validated['firstname'],
                        'surname'   => $validated['surname'],
                        'birthname' => $validated['birthname'],
                        'nickname'  => $validated['nickname'],
                        'sex'       => $validated['sex'],
                        'gender_id' => $validated['gender_id'] ?? null,
                        'yob'       => $validated['yob'],
                        'dob'       => $validated['dob'],
                        'pob'       => $validated['pob'],
                        'mother_id' => $this->person->id,
                        'team_id'   => $this->person->team_id,
                    ]);
                }

                if ($this->photos) {
                    // if needed, create team photo folder
                    $path = storage_path('app/public/photos/' . $new_person->team_id);

                    if (! File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }

                    // set image parameters
                    $image_width   = config('app.image_upload_max_width');
                    $image_height  = config('app.image_upload_max_height');
                    $image_quality = config('app.image_upload_quality');
                    $image_type    = config('app.image_upload_type');

                    // set image manager
                    $manager = new ImageManager(new Driver());

                    $last_index = 0;

                    foreach ($this->photos as $current_photo) {
                        // name
                        $next_index = str_pad(++$last_index, 3, '0', STR_PAD_LEFT);
                        $image_name = $new_person->id . '_' . $next_index . '_' . now()->format('YmdHis') . '.' . $image_type;

                        // resize, add watermark
                        $new_image = $manager->read($current_photo)
                            ->scaleDown(width: $image_width, height: $image_height)
                            ->place(public_path('img/watermark.png'), 'bottom-left', 5, 5)
                            ->toWebp(quality: $image_quality);

                        // save
                        if ($new_image) {
                            $new_image->save(storage_path('app/public/photos/' . $new_person->team_id . '/' . $image_name));

                            if (! isset($new_person->photo)) {
                                $new_person->update(['photo' => $image_name]);
                            }
                        } else {
                            $this->toast()->error(__('app.save'), __('app.image_not_saved') . '.')->flash()->send();
                        }
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
        $this->childForm->firstname != null or
        $this->childForm->surname != null or
        $this->childForm->birthname != null or
        $this->childForm->nickname != null or

        $this->childForm->sex != null or
        $this->childForm->gender_id != null or

        $this->childForm->yob != null or
        $this->childForm->dob != null or
        $this->childForm->pob != null or

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
                        'id'   => $p->id,
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
                        'id'   => $p->id,
                        'name' => $p->name . ' [' . strtoupper($p->sex) . '] ' . ($p->birth_formatted ? '(' . $p->birth_formatted . ')' : ''),
                    ];
                })->toArray();
        }

        return view('livewire.people.add.child')->with(compact('persons'));
    }
}
