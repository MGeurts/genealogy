<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Facades\MediaLibrary;
use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Partner extends Component
{
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    public PersonForm $form;

    public Collection $persons;

    public ?string $selectedTab = null;

    // -----------------------------------------------------------------------
    public $date_start = null;

    public $date_end = null;

    public $is_married = false;

    public $has_ended = false;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->persons = Person::PartnerOffset($this->person->birth_year)
            ->where('id', '!=', $this->person->id)
            ->orderBy('firstname')->orderBy('surname')
            ->get()
            ->map(fn ($p): array => [
                'id'   => $p->id,
                'name' => $p->name . ' [' . (($p->sex === 'm') ? __('app.male') : __('app.female')) . '] ' . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
            ]);

        $this->selectedTab = $this->persons->isEmpty() ? __('person.add_new_person_as_partner') : __('person.add_existing_person_as_partner');
    }

    /**
     * Handle updates to the uploads property.
     */
    public function updatingUploads(): void
    {
        $this->form->backup = $this->form->uploads;
    }

    /**
     * Process uploaded files and remove duplicates.
     */
    public function updatedUploads(): void
    {
        if (empty($this->form->uploads)) {
            return;
        }

        $this->form->uploads = collect(array_merge($this->form->backup, (array) $this->form->uploads))
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->toArray();
    }

    /**
     * Handle file deletion from uploads.
     */
    public function deleteUpload(array $content): void
    {
        /* the $content contains:
            [
                'temporary_name',
                'real_name',
                'extension',
                'size',
                'path',
                'url',
            ]
        */

        if (empty($this->form->uploads)) {
            return;
        }

        $this->form->uploads = collect($this->form->uploads)
            ->filter(fn (UploadedFile $file): bool => $file->getFilename() !== $content['temporary_name'])
            ->values()
            ->toArray();

        rescue(
            fn () => File::delete(storage_path('app/livewire-tmp/' . $content['temporary_name'])),
            report: false
        );
    }

    public function savePartner(): void
    {
        $validated = $this->validate($this->rules());

        // Ensure has_ended is true if date_end is filled
        if ($validated['date_end'] && ! $validated['has_ended']) {
            $this->addError('has_ended', __('couple.required_if_date_end'));

            return;
        }

        if (isset($validated['form']['person_id'])) {
            if ($this->hasOverlap($validated['date_start'], $validated['date_end'])) {
                $this->toast()->error(__('app.create'), __('couple.overlap'))->send();
            } else {
                $couple = Couple::create([
                    'person1_id' => $this->person->id,
                    'person2_id' => $validated['form']['person_id'],
                    'date_start' => $validated['date_start'] ?? null,
                    'date_end'   => $validated['date_end'] ?? null,
                    'is_married' => $validated['is_married'],
                    'has_ended'  => $validated['has_ended'],
                    'team_id'    => $this->person->team_id,
                ]);

                $this->toast()->success(__('app.create'), $couple->name . ' ' . __('app.created'))->send();

                $this->redirect(route('people.show', $this->person->id));
            }
        } else {
            if ($this->hasOverlap($validated['date_start'], $validated['date_end'])) {
                $this->toast()->error(__('app.create'), __('couple.overlap'))->send();
            } else {
                $newPartner = Person::create([
                    'firstname' => $validated['form']['firstname'],
                    'surname'   => $validated['form']['surname'],
                    'birthname' => $validated['form']['birthname'],
                    'nickname'  => $validated['form']['nickname'],
                    'sex'       => $validated['form']['sex'],
                    'gender_id' => $validated['form']['gender_id'] ?? null,
                    'yob'       => $validated['form']['yob'],
                    'dob'       => $validated['form']['dob'],
                    'pob'       => $validated['form']['pob'],
                    'team_id'   => $this->person->team_id,
                ]);

                if ($savedCount = MediaLibrary::savePhotosToPerson($newPartner, $this->form->uploads)) {
                    $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', $savedCount))->send();
                }

                $this->toast()->success(__('app.create'), $newPartner->name . ' ' . __('app.created') . '.')->send();

                $couple = Couple::create([
                    'person1_id' => $this->person->id,
                    'person2_id' => $newPartner->id,
                    'date_start' => $validated['date_start'] ?? null,
                    'date_end'   => $validated['date_end'] ?? null,
                    'is_married' => $validated['is_married'],
                    'has_ended'  => $validated['has_ended'],
                    'team_id'    => $this->person->team_id,
                ]);

                $this->toast()->success(__('app.create'), $couple->name . ' ' . __('app.created') . '.')->flash()->send();

                $this->redirect(route('people.show', $this->person->id));
            }
        }
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.add.partner');
    }

    // -----------------------------------------------------------------------

    protected function rules(): array
    {
        return [
            'form.firstname' => ['nullable', 'string', 'max:255'],
            'form.surname'   => ['nullable', 'string', 'max:255', 'required_without:form.person_id', 'required_with:form.sex'],
            'form.birthname' => ['nullable', 'string', 'max:255'],
            'form.nickname'  => ['nullable', 'string', 'max:255'],
            'form.sex'       => ['nullable', 'string', 'max:1', 'in:m,f', 'required_without:form.person_id', 'required_with:form.surname'],
            'form.gender_id' => ['nullable', 'integer'],
            'form.yob'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y'), new YobValid],
            'form.dob'       => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', new DobValid],
            'form.pob'       => ['nullable', 'string', 'max:255'],
            'form.uploads.*' => [
                'file',
                'mimetypes:' . implode(',', array_keys(config('app.upload_photo_accept'))),
                'max:' . config('app.upload_max_size'),
            ],

            'form.person_id' => ['nullable', 'integer', 'required_without_all:form.surname, form.sex', 'exists:people,id'],

            // -----------------------------------------------------------------------
            'date_start' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'before:date_end'],
            'date_end'   => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'after:date_start'],
            'is_married' => ['nullable', 'boolean'],
            'has_ended'  => ['nullable', 'boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'form.surname.required_without'   => __('validation.surname.required_without'),
            'form.sex.required_without'       => __('validation.sex.required_without'),
            'form.person_id.required_without' => __('validation.person_id.required_without'),
            'has_ended.required_if'           => __('couple.custom.required_if_date_end'),

            'form.uploads.*.file'      => __('validation.file', ['attribute' => __('person.photos')]),
            'form.uploads.*.mimetypes' => __('validation.mimetypes', [
                'attribute' => __('person.photos'),
                'values'    => implode(', ', array_values(config('app.upload_photo_accept'))),
            ]),
            'form.uploads.*.max' => __('validation.max.file', [
                'attribute' => __('person.photos'),
                'max'       => config('app.upload_max_size'),
            ]),
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'form.firstname' => __('person.firstname'),
            'form.surname'   => __('person.surname'),
            'form.birthname' => __('person.birthname'),
            'form.nickname'  => __('person.nickname'),
            'form.sex'       => __('person.sex'),
            'form.gender_id' => __('person.gender'),
            'form.yob'       => __('person.yob'),
            'form.dob'       => __('person.dob'),
            'form.pob'       => __('person.pob'),
            'form.uploads'   => __('person.photos'),

            'form.person_id' => __('person.person'),

            'date_start' => __('couple.date_start'),
            'date_end'   => __('couple.date_end'),
            'is_married' => __('couple.is_married'),
            'has_ended'  => __('couple.has_ended'),
        ];
    }

    // -----------------------------------------------------------------------
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
}
