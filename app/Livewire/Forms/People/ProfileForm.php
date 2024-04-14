<?php

namespace App\Livewire\Forms\People;

use App\Models\Gender;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProfileForm extends Form
{
    // -----------------------------------------------------------------------
    public $person;

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

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function genders()
    {
        return Gender::select('id', 'name')->orderBy('name')->get()->toArray();
    }

    public function rules()
    {
        return [
            'firstname' => ['nullable', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'birthname' => ['nullable', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],

            'sex' => ['required', 'in:m,f'],
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
        ];
    }

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'firstname' => __('person.firstname'),
            'surname' => __('person.surname'),
            'birthname' => __('person.birthname'),
            'nickname' => __('person.nickname'),

            'sex' => __('person.sex'),
            'gender_id' => __('person.gender'),

            'yob' => __('person.yob'),
            'dob' => __('person.dob'),
            'pob' => __('person.pob'),
        ];
    }
}
