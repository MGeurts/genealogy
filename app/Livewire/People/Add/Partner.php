<?php

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PartnerForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use App\Tools\Photos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Partner extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public PartnerForm $partnerForm;

    public $photos = [];

    public $backup = [];

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->partnerForm->firstname = null;
        $this->partnerForm->surname   = null;
        $this->partnerForm->birthname = null;
        $this->partnerForm->nickname  = null;

        $this->partnerForm->sex       = null;
        $this->partnerForm->gender_id = null;

        $this->partnerForm->yob = null;
        $this->partnerForm->dob = null;
        $this->partnerForm->pob = null;

        $this->partnerForm->photo = null;

        $this->partnerForm->person2_id = null;

        $this->partnerForm->date_start = null;
        $this->partnerForm->date_end   = null;

        $this->partnerForm->is_married = false;
        $this->partnerForm->has_ended  = false;
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

    public function savePartner()
    {
        if ($this->isDirty()) {
            $validated = $this->partnerForm->validate();

            if (isset($validated['person2_id'])) {
                if ($this->hasOverlap($validated['date_start'], $validated['date_end'])) {
                    $this->toast()->error(__('app.create'), 'RELATIONSHIP OVERLAP !!')->send();
                } else {
                    $couple = Couple::create([
                        'person1_id' => $this->person->id,
                        'person2_id' => $validated['person2_id'],
                        'date_start' => $validated['date_start'] ?? null,
                        'date_end'   => $validated['date_end'] ?? null,
                        'is_married' => $validated['is_married'],
                        'has_ended'  => $validated['has_ended'],
                        'team_id'    => $this->person->team_id,
                    ]);

                    $this->toast()->success(__('app.create'), $couple->name . ' ' . __('app.created'))->flash()->send();
                }
            } else {
                if ($this->hasOverlap($validated['date_start'], $validated['date_end'])) {
                    $this->toast()->error(__('app.create'), 'RELATIONSHIP OVERLAP !!')->send();
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
                        'team_id'   => $this->person->team_id,
                    ]);

                    if ($this->photos) {
                        Photos::save($new_person, $this->photos);
                    }

                    $this->toast()->success(__('app.create'), $new_person->name . ' ' . __('app.created') . '.')->flash()->send();

                    $couple = Couple::create([
                        'person1_id' => $this->person->id,
                        'person2_id' => $new_person->id,
                        'date_start' => $validated['date_start'] ?? null,
                        'date_end'   => $validated['date_end'] ?? null,
                        'is_married' => $validated['is_married'],
                        'has_ended'  => $validated['has_ended'],
                        'team_id'    => $this->person->team_id,
                    ]);

                    $this->toast()->success(__('app.create'), $couple->name . ' ' . __('app.created') . '.')->flash()->send();
                }
            }

            return $this->redirect('/people/' . $this->person->id);
        }
    }

    public function resetPartner(): void
    {
        $this->mount();
    }

    private function hasOverlap($start, $end): bool
    {
        $is_overlap = false;

        if (! empty($start) or ! empty($end)) {
            foreach ($this->person->couples as $couple) {
                if (! empty($couple->date_start) and ! empty($couple->date_end)) {
                    if (! empty($start) and $start >= $couple->date_start and $start <= $couple->date_end) {
                        $is_overlap = true;
                    } elseif (! empty($end) and $end >= $couple->date_start and $end <= $couple->date_end) {
                        $is_overlap = true;
                    }
                }
            }
        }

        return $is_overlap;
    }

    public function isDirty(): bool
    {
        return
        $this->partnerForm->firstname != null or
        $this->partnerForm->surname != null or
        $this->partnerForm->birthname != null or
        $this->partnerForm->nickname != null or

        $this->partnerForm->sex != null or
        $this->partnerForm->gender_id != null or

        $this->partnerForm->yob != null or
        $this->partnerForm->dob != null or
        $this->partnerForm->pob != null or

        $this->partnerForm->person2_id != null or
        $this->partnerForm->date_start != null or
        $this->partnerForm->date_end != null or
        $this->partnerForm->is_married != false or
        $this->partnerForm->has_ended != false;
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        $persons = Person::PartnerOffset($this->person->birth_date, $this->person->birth_year)
            ->orderBy('firstname', 'asc')->orderBy('surname', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'id'   => $p->id,
                    'name' => $p->name . ' [' . strtoupper($p->sex) . '] (' . $p->birth_formatted . ')',
                ];
            });

        return view('livewire.people.add.partner')->with(compact('persons'));
    }
}
