<?php

declare(strict_types=1);

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\HandlesPhotoUploads;
use App\Livewire\Traits\SavesPersonPhotos;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person as PersonModel;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use HandlesPhotoUploads, SavesPersonPhotos;
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    public PersonForm $form;

    /** Controls whether the similar-persons results pane is visible. */
    public bool $searchTriggered = false;

    #[Computed]
    public function similarPersons(): Collection
    {
        $user = auth()->user();

        if (! $user || ! $user->currentTeam) {
            return new Collection;
        }

        $nameFields = [
            $this->form->firstname,
            $this->form->surname,
            $this->form->birthname,
            $this->form->nickname,
        ];

        $hasMinLength = collect($nameFields)->contains(
            fn ($value) => mb_strlen((string) $value) >= 3
        );

        // only query if at least 1 name has at least 3 characters
        if (! $hasMinLength) {
            return new Collection;
        }

        $teamId = $user->isDeveloper() ? null : $user->currentTeam->id;

        return PersonModel::similarTo($teamId, $nameFields)->get();
    }

    /**
     * Manually trigger the similar-persons search and show the results pane.
     */
    public function searchSimilar(): void
    {
        $this->searchTriggered = true;
        unset($this->similarPersons); // bust the computed cache so it re-runs
    }

    /**
     * Hide the similar-persons results pane and reset the trigger flag.
     */
    public function clearSimilar(): void
    {
        $this->searchTriggered = false;
        unset($this->similarPersons); // bust cache, mirrors searchSimilar()
    }

    public function savePerson(): void
    {
        $user = auth()->user();

        if (! $user || ! $user->currentTeam) {
            return;
        }

        $validated = $this->validate($this->rules());

        $newPerson = PersonModel::create([
            'firstname' => $validated['form']['firstname'],
            'surname'   => $validated['form']['surname'],
            'birthname' => $validated['form']['birthname'],
            'nickname'  => $validated['form']['nickname'],
            'sex'       => $validated['form']['sex'],
            'gender_id' => $validated['form']['gender_id'] ?? null,
            'yob'       => $validated['form']['yob'],
            'dob'       => $validated['form']['dob'],
            'pob'       => $validated['form']['pob'],
            'team_id'   => $user->currentTeam->id,
        ]);

        // Handle photo uploads if present, using SavesPersonPhotos trait
        if (! empty($this->form->uploads)) {
            $this->savePersonPhotos($newPerson, 'person');
        }

        $this->toast()->success(__('app.create'), e($newPerson->name) . ' ' . __('app.created'))->send();

        $this->redirect('/people/' . $newPerson->id);
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return array_merge([
            'form.firstname' => ['nullable', 'string', 'max:255'],
            'form.surname'   => ['required', 'string', 'max:255'],
            'form.birthname' => ['nullable', 'string', 'max:255'],
            'form.nickname'  => ['nullable', 'string', 'max:255'],
            'form.sex'       => ['required', 'string', 'max:1', 'in:m,f'],
            'form.gender_id' => ['nullable', 'integer'],
            'form.yob'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y'), new YobValid],
            'form.dob'       => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', new DobValid],
            'form.pob'       => ['nullable', 'string', 'max:255'],
        ], $this->getPhotoUploadRules());
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return $this->getPhotoUploadMessages();
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
        ], $this->getPhotoUploadAttributes());
    }
};
