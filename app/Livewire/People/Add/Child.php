<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\HandlesPhotoUploads;
use App\Livewire\Traits\SavesPersonPhotos;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Child extends Component
{
    use HandlesPhotoUploads, SavesPersonPhotos;
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    public Person $person;

    public PersonForm $form;

    public Collection $persons;

    public ?string $selectedTab = null;

    public function mount(): void
    {
        $this->persons = Person::where('id', '!=', $this->person->id)
            ->whereNull($this->person->sex === 'm' ? 'father_id' : 'mother_id')
            ->youngerThan($this->person->dob, $this->person->yob)
            ->olderThan($this->person->dod, $this->person->yod)
            ->orderBy('firstname')
            ->orderBy('surname')
            ->get()
            ->map(fn ($p): array => [
                'id'   => $p->id,
                'name' => $p->name . ' [' . ($p->sex === 'm' ? __('app.male') : __('app.female')) . '] ' . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
            ]);

        $this->selectedTab = $this->persons->isEmpty() ? __('person.add_new_person_as_child') : __('person.add_existing_person_as_child');
    }

    public function saveChild(): void
    {
        $validated = $this->validate();

        if (isset($validated['form']['person_id'])) {
            $this->linkExistingChild($validated['form']['person_id']);
        } else {
            $this->createNewChild($validated['form']);
        }

        $this->redirect(route('people.show', $this->person->id));
    }

    public function render(): View
    {
        return view('livewire.people.add.child');
    }

    // -----------------------------------------------------------------------
    // Protected Methods
    // -----------------------------------------------------------------------

    /**
     * Link an existing person as a child.
     */
    protected function linkExistingChild(int $personId): void
    {
        $child = Person::findOrFail($personId);

        $child->update([
            $this->person->sex === 'm' ? 'father_id' : 'mother_id' => $this->person->id,
        ]);

        $this->toast()->success(__('app.save'), __('person.existing_person_linked_as_child'))->send();
    }

    /**
     * Create a new person and link as a child.
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

        $this->toast()->success(__('app.create'), __('person.new_person_linked_as_child'))->flash()->send();
    }

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

    protected function messages(): array
    {
        return array_merge([
            'form.surname.required_without' => __('validation.surname.required_without'),
            'form.sex.required_without'     => __('validation.sex.required_without'),

            'form.person_id.required_without' => __('validation.person_id.required_without'),
        ], $this->getPhotoUploadMessages());
    }

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
}
