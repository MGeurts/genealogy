<?php

namespace App\Livewire\Forms\People;

use Livewire\Form;

class ChildForm extends Form
{
    // -----------------------------------------------------------------------
    public $firstname = null;

    public $surname = null;

    public $sex = null;

    public $gender_id = null;

    public $person_id = null;

    // -----------------------------------------------------------------------
    protected $rules = [
        'firstname' => ['required_without:person_id', 'nullable', 'string', 'max:255'],
        'surname' => ['required_without:person_id', 'nullable', 'string', 'max:255'],
        'sex' => ['required_without:person_id', 'nullable', 'in:m,f'],
        'gender_id' => ['nullable', 'integer'],

        'person_id' => ['required_without_all:firstname,surname,sex', 'nullable', 'integer'],
    ];

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'firstname' => __('person.firstname'),
            'surname' => __('person.surname'),
            'sex' => __('person.sex'),
            'gender_id' => __('person.gender'),

            'person_id' => __('person.person'),
        ];
    }
}
