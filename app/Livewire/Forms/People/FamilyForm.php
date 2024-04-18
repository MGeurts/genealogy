<?php

namespace App\Livewire\Forms\People;

use Livewire\Form;

class FamilyForm extends Form
{
    // -----------------------------------------------------------------------
    public $father_id = null;

    public $mother_id = null;

    public $parents_id = null;

    // -----------------------------------------------------------------------
    protected $rules = [
        'father_id'  => ['nullable', 'integer'],
        'mother_id'  => ['nullable', 'integer'],
        'parents_id' => ['nullable', 'integer'],
    ];

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'father_id'  => __('person.father'),
            'mother_id'  => __('person.mother'),
            'parents_id' => __('parents.father'),
        ];
    }
}
