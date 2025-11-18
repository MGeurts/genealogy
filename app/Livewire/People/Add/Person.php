<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\HandlesPhotoUploads;
use App\Livewire\Traits\SavesPersonPhotos;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person as PersonModel;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

final class Person extends Component
{
    use HandlesPhotoUploads, SavesPersonPhotos;
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    public PersonForm $form;

    public function mount(): void {}

    public function savePerson(): void
    {
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
            'team_id'   => auth()->user()->currentTeam->id,
        ]);

        // Handle photo uploads if present, using SavesPersonPhotos trait
        if (! empty($this->form->uploads)) {
            $this->savePersonPhotos($newPerson, 'person');
        }

        $this->toast()->success(__('app.save'), e($newPerson->name) . ' ' . __('app.created'))->flash()->send();

        $this->redirect('/people/' . $newPerson->id);
    }

    public function render(): View
    {
        return view('livewire.people.add.person');
    }

    // -----------------------------------------------------------------------
    // Protected Methods
    // -----------------------------------------------------------------------

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

    protected function messages(): array
    {
        return $this->getPhotoUploadMessages();
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
        ], $this->getPhotoUploadAttributes());
    }
}
