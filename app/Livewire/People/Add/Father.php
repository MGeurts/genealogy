<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\HandlesPhotoUploads;
use App\Livewire\Traits\SavesPersonPhotos;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Father extends Component
{
    use HandlesPhotoUploads, SavesPersonPhotos;
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    public Person $person;

    public PersonForm $form;

    /**
     * @var Collection<int, array{id: int, name: string}>
     */
    public Collection $persons;

    public ?string $selectedTab = null;

    public function mount(): void
    {
        $this->persons = Person::where('id', '!=', $this->person->id)
            ->where('sex', 'm')
            ->olderThan($this->person->dob, $this->person->yob)
            ->orderBy('firstname')
            ->orderBy('surname')
            ->get()
            ->map(fn ($p): array => [
                'id'   => $p->id,
                'name' => $p->name . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
            ]);

        $this->selectedTab = $this->persons->isEmpty() ? __('person.add_new_person_as_father') : __('person.add_existing_person_as_father');
    }

    public function saveFather(): void
    {
        $validated = $this->validate($this->rules());

        if (isset($validated['form']['person_id'])) {
            $this->linkExistingFather($validated['form']['person_id']);
        } else {
            $this->createNewFather($validated['form']);
        }

        $this->redirect(route('people.show', $this->person->id));
    }

    public function render(): View
    {
        return view('livewire.people.add.father');
    }

    // -----------------------------------------------------------------------
    // Protected Methods
    // -----------------------------------------------------------------------

    /**
     * Link an existing person as father.
     */
    protected function linkExistingFather(int $personId): void
    {
        $this->person->update([
            'father_id' => $personId,
        ]);

        $this->toast()->success(__('app.save'), __('person.existing_person_linked_as_father'))->send();
    }

    /**
     * Create a new person and link as father.
     *
     * @param  array<string, mixed>  $validated
     */
    protected function createNewFather(array $validated): void
    {
        $newFather = Person::create(array_merge(
            collect($validated)->only(['firstname', 'surname', 'birthname', 'nickname', 'gender_id', 'yob', 'dob', 'pob'])->toArray(),
            [
                'sex'     => 'm',
                'team_id' => $this->person->team_id,
            ]
        ));

        // Handle photo uploads if present
        if (! empty($this->form->uploads)) {
            $this->savePersonPhotos($newFather, 'father');
        }

        $this->person->update([
            'father_id' => $newFather->id,
        ]);

        $this->toast()->success(__('app.create'), __('person.new_person_linked_as_father'))->send();
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
            'form.gender_id' => ['nullable', 'integer'],
            'form.yob'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y'), new YobValid],
            'form.dob'       => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', new DobValid],
            'form.pob'       => ['nullable', 'string', 'max:255'],

            'form.person_id' => ['nullable', 'integer', 'exists:people,id', 'required_without:form.surname'],
        ], $this->getPhotoUploadRules());
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return array_merge([
            'form.surname.required_without' => __('validation.surname.required_without'),

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
            'form.gender_id' => __('person.gender'),
            'form.yob'       => __('person.yob'),
            'form.dob'       => __('person.dob'),
            'form.pob'       => __('person.pob'),

            'form.person_id' => __('person.person'),
        ], $this->getPhotoUploadAttributes());
    }
}
