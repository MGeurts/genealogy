<?php

namespace App\Livewire\Forms\People;

use App\Models\Gender;
use Livewire\Attributes\Computed;
use Livewire\Form;

class ChildForm extends Form
{
    // -----------------------------------------------------------------------
    public $image = null; // file upload input

    public $iteration = 0; // needed for reset upload input

    // -----------------------------------------------------------------------
    public $firstname = null;

    public $surname = null;

    public $sex = null;

    public $gender_id = null;

    public $photo = null;

    public $person_id = null;

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function genders()
    {
        return Gender::select('id', 'name')->orderBy('name')->get()->toArray();
    }

    // -----------------------------------------------------------------------
    protected $rules = [
        'firstname' => ['required_without:person_id', 'nullable', 'string', 'max:255'],
        'surname'   => ['required_without:person_id', 'nullable', 'string', 'max:255'],
        'sex'       => ['required_without:person_id', 'nullable', 'in:m,f'],
        'gender_id' => ['nullable', 'integer'],

        'photo' => ['nullable', 'string', 'max:255'],
        'image' => ['nullable', 'sometimes', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:1024'],

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
            'surname'   => __('person.surname'),
            'sex'       => __('person.sex'),
            'gender_id' => __('person.gender'),

            'photo' => __('person.photo'),
            'image' => __('person.photo'),

            'person_id' => __('person.person'),
        ];
    }
}
