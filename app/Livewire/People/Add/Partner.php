<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\HandlesPhotoUploads;
use App\Livewire\Traits\SavesPersonPhotos;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Partner extends Component
{
    use HandlesPhotoUploads, SavesPersonPhotos;
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    public Person $person;

    public PersonForm $form;

    /** @var Collection<int, array{id: int, name: string}> */
    public Collection $persons;

    public ?string $selectedTab = null;

    public ?string $date_start = null;

    public ?string $date_end = null;

    public bool $is_married = false;

    public bool $has_ended = false;

    public function mount(): void
    {
        $this->persons = Person::partnerOffset($this->person->dob, $this->person->yob)
            ->where('id', '!=', $this->person->id)
            ->orderBy('firstname')
            ->orderBy('surname')
            ->get()
            ->map(fn ($p): array => [
                'id'   => $p->id,
                'name' => $p->name . ' [' . (($p->sex === 'm') ? __('app.male') : __('app.female')) . '] ' . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
            ]);

        $this->selectedTab = $this->persons->isEmpty() ? __('person.add_new_person_as_partner') : __('person.add_existing_person_as_partner');
    }

    public function savePartner(): void
    {
        $validated = $this->validate($this->rules());

        // Ensure has_ended is true if date_end is given
        if (! empty($validated['date_end']) && empty($validated['has_ended'])) {
            $this->addError('has_ended', __('couple.required_if_date_end'));

            return;
        }

        // Check for date overlap
        if ($this->hasOverlap($validated['date_start'] ?? null, $validated['date_end'] ?? null)) {
            $this->toast()->error(__('app.create'), __('couple.overlap'))->send();

            return;
        }

        if (isset($validated['form']['person_id'])) {
            $this->linkExistingPartner($validated);
        } else {
            $this->createNewPartner($validated);
        }

        $this->redirect(route('people.show', $this->person->id));
    }

    public function render(): View
    {
        return view('livewire.people.add.partner');
    }

    // -----------------------------------------------------------------------
    // Protected Methods
    // -----------------------------------------------------------------------

    /**
     * Link an existing person as partner.
     *
     * @param  array<string, mixed>  $validated
     */
    protected function linkExistingPartner(array $validated): void
    {
        $couple = Couple::create([
            'person1_id' => $this->person->id,
            'person2_id' => $validated['form']['person_id'],
            'date_start' => $validated['date_start'] ?? null,
            'date_end'   => $validated['date_end'] ?? null,
            'is_married' => $validated['is_married'],
            'has_ended'  => $validated['has_ended'],
            'team_id'    => $this->person->team_id,
        ]);

        $this->toast()->success(__('app.create'), e($couple->name) . ' ' . __('app.created'))->send();
    }

    /**
     * Create a new person and link as partner.
     *
     * @param  array<string, mixed>  $validated
     */
    protected function createNewPartner(array $validated): void
    {
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

        // Handle photo uploads if present
        if (! empty($this->form->uploads)) {
            $this->savePersonPhotos($newPartner, 'partner');
        }

        $this->toast()->success(__('app.create'), e($newPartner->name) . ' ' . __('app.created') . '.')->send();

        $couple = Couple::create([
            'person1_id' => $this->person->id,
            'person2_id' => $newPartner->id,
            'date_start' => $validated['date_start'] ?? null,
            'date_end'   => $validated['date_end'] ?? null,
            'is_married' => $validated['is_married'],
            'has_ended'  => $validated['has_ended'],
            'team_id'    => $this->person->team_id,
        ]);

        $this->toast()->success(__('app.create'), e($couple->name) . ' ' . __('app.created') . '.')->flash()->send();
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return array_merge([
            'form.firstname' => ['nullable', 'string', 'max:255'],
            'form.surname'   => ['nullable', 'string', 'max:255', 'required_without:form.person_id', 'required_with:form.sex'],
            'form.birthname' => ['nullable', 'string', 'max:255'],
            'form.nickname'  => ['nullable', 'string', 'max:255'],
            'form.sex'       => ['nullable', 'string', 'max:1', 'in:m,f', 'required_without:form.person_id', 'required_with:form.surname'],
            'form.gender_id' => ['nullable', 'integer'],
            'form.yob'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y'), new YobValid],
            'form.dob'       => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', new DobValid],
            'form.pob'       => ['nullable', 'string', 'max:255'],

            'form.person_id' => ['nullable', 'integer', 'required_without_all:form.surname,form.sex', 'exists:people,id'],

            'date_start' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'before:date_end'],
            'date_end'   => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'after:date_start'],
            'is_married' => ['nullable', 'boolean'],
            'has_ended'  => ['nullable', 'boolean'],
        ], $this->getPhotoUploadRules());
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return array_merge([
            'form.surname.required_without' => __('validation.surname.required_without'),
            'form.sex.required_without'     => __('validation.sex.required_without'),

            'form.person_id.required_without' => __('validation.person_id.required_without'),
        ], $this->getPhotoUploadMessages());
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return array_merge([
            'form.firstname' => __('person.firstname'),
            'form.surname'   => __('person.surname'),
            'form.birthname' => __('person.birthname'),
            'form.nickname'  => __('person.nickname'),
            'form.sex'       => __('person.sex'),
            'form.gender_id' => __('person.gender'),
            'form.yob'       => __('person.yob'),
            'form.dob'       => __('person.dob'),
            'form.pob'       => __('person.pob'),

            'form.person_id' => __('person.person'),

            'date_start' => __('couple.date_start'),
            'date_end'   => __('couple.date_end'),
            'is_married' => __('couple.is_married'),
            'has_ended'  => __('couple.has_ended'),
        ], $this->getPhotoUploadAttributes());
    }

    /**
     * Check if couple dates overlap with existing couples.
     */
    private function hasOverlap(?string $start, ?string $end): bool
    {
        if (empty($start) && empty($end)) {
            return false;
        }

        foreach ($this->person->couples as $couple) {
            if (empty($couple->date_start) || empty($couple->date_end)) {
                continue;
            }

            // Check if new start date falls within existing couple period
            if (! empty($start) && $start >= $couple->date_start && $start <= $couple->date_end) {
                return true;
            }

            // Check if new end date falls within existing couple period
            if (! empty($end) && $end >= $couple->date_start && $end <= $couple->date_end) {
                return true;
            }

            // Check if new period completely encompasses existing period
            if (! empty($start) && ! empty($end) && $start <= $couple->date_start && $end >= $couple->date_end) {
                return true;
            }
        }

        return false;
    }
}
