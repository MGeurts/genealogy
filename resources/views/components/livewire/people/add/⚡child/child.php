<?php

declare(strict_types=1);

use App\Livewire\Forms\People\PersonForm;
use App\Models\Person;
use Livewire\Component;

new class extends Component
{
    use App\Livewire\Traits\AuthorizesPersonActions;
    use App\Livewire\Traits\HandlesPhotoUploads, \App\Livewire\Traits\SavesPersonPhotos;
    use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
    use \Livewire\WithFileUploads, TallStackUi\Traits\Interactions;

    public Person $person;

    public PersonForm $form;

    public ?string $selectedTab = null;

    public function mount(): void
    {
        $this->form->reset();

        $this->selectedTab = __('person.add_new_person_as_child');
    }

    public function saveChild(): void
    {
        $this->authorizePermission('person:create');

        $validated = $this->validate();

        if (isset($validated['form']['person_id'])) {
            $this->linkExistingChild($validated['form']['person_id']);
        } else {
            $this->createNewChild($validated['form']);
        }

        $this->dispatch('person_added_as_child');
    }

    /**
     * Link an existing person as a child.
     */
    protected function linkExistingChild(int $personId): void
    {
        /** @var Person $child */
        $child = Person::query()
            ->whereKey($personId)
            ->where('team_id', $this->person->team_id)
            ->whereNull($this->person->sex === 'm' ? 'father_id' : 'mother_id')
            ->youngerThan($this->person->dob, $this->person->yob)
            ->olderThan($this->person->dod, $this->person->yod)
            ->firstOrFail();

        $child->update([
            $this->person->sex === 'm' ? 'father_id' : 'mother_id' => $this->person->id,
        ]);

        $this->toast()->success(__('app.save'), __('person.existing_person_linked_as_child'))->send();
    }

    /**
     * Create a new person and link as a child.
     *
     * @param  array<string, mixed>  $validated
     */
    protected function createNewChild(array $validated): void
    {
        $newChild = Person::create(array_merge(
            collect($validated)->only(['firstname', 'surname', 'birthname', 'nickname', 'sex', 'gender_id', 'yob', 'dob', 'pob'])->toArray(),
            [
                $this->person->sex === 'm' ? 'father_id' : 'mother_id' => $this->person->id,
                'team_id'                                              => $this->person->team_id,
            ]
        ));

        // Handle photo uploads if present
        if (! empty($this->form->uploads)) {
            $this->savePersonPhotos($newChild, 'child');
        }

        $this->toast()->success(__('app.create'), __('person.new_person_linked_as_child'))->send();
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return array_merge([
            'form.firstname' => ['nullable', 'string', 'max:255'],
            'form.surname'   => ['nullable', 'string', 'max:255', 'required_without:form.person_id'],
            'form.birthname' => ['nullable', 'string', 'max:255'],
            'form.nickname'  => ['nullable', 'string', 'max:255'],
            'form.sex'       => ['nullable', 'string', 'max:1', 'in:m,f', 'required_without:form.person_id'],
            'form.gender_id' => ['nullable', 'integer'],
            'form.yob'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y')],
            'form.dob'       => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
            'form.pob'       => ['nullable', 'string', 'max:255'],

            'form.person_id' => ['nullable', 'integer', 'exists:people,id', 'required_without_all:form.surname,form.sex'],
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
        ], $this->getPhotoUploadAttributes());
    }
};
