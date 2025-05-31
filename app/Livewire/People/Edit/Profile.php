<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Gender;
use App\Models\Person;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Profile extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public $firstname = null;

    public $surname = null;

    public $birthname = null;

    public $nickname = null;

    public $sex = null;

    public $gender_id = null;

    #[Validate]
    public $yob = null;

    #[Validate]
    public $dob = null;

    public $pob = null;

    public $summary = null;

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function genders(): Collection
    {
        return Gender::select('id', 'name')->orderBy('name')->get();
    }

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();
    }

    public function saveProfile(): void
    {
        $validated = $this->validate();

        $this->person->update($validated);

        $this->toast()->success(__('app.save'), __('app.saved'))->flash()->send();

        $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.profile');
    }

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return [
            'firstname' => ['nullable', 'string', 'max:255'],
            'surname'   => ['required', 'string', 'max:255'],
            'birthname' => ['nullable', 'string', 'max:255'],
            'nickname'  => ['nullable', 'string', 'max:255'],

            'sex'       => ['required', 'string', 'max:1', 'in:m,f'],
            'gender_id' => ['nullable', 'integer'],

            'yob' => [
                'nullable',
                'integer',
                'min:1',
                'max:' . date('Y'),
                new YobValid,
            ],
            'dob' => [
                'nullable',
                'date_format:Y-m-d',
                'before_or_equal:today',
                new DobValid,
            ],
            'pob' => ['nullable', 'string', 'max:255'],

            'summary' => ['nullable', 'string', 'max:65535'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [
            'firstname' => __('person.firstname'),
            'surname'   => __('person.surname'),
            'birthname' => __('person.birthname'),
            'nickname'  => __('person.nickname'),

            'sex'       => __('person.sex'),
            'gender_id' => __('person.gender'),

            'yob' => __('person.yob'),
            'dob' => __('person.dob'),
            'pob' => __('person.pob'),

            'summary' => __('person.summary'),
        ];
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->firstname = $this->person->firstname;
        $this->surname   = $this->person->surname;
        $this->birthname = $this->person->birthname;
        $this->nickname  = $this->person->nickname;
        $this->sex       = $this->person->sex;
        $this->gender_id = $this->person->gender_id;
        $this->yob       = $this->person->yob ?? null;
        $this->dob       = $this->person->dob ? Carbon::parse($this->person->dob)->format('Y-m-d') : null;
        $this->pob       = $this->person->pob;
        $this->summary   = $this->person->summary;
    }
}
