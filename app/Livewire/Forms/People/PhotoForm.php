<?php

namespace App\Livewire\Forms\People;

use Livewire\Form;

class PhotoForm extends Form
{
    // -----------------------------------------------------------------------
    public $image = null;       // file upload input

    public $iteration = 0;      // needed for reset upload input

    // -----------------------------------------------------------------------
    public $photo = null;

    // -----------------------------------------------------------------------
    protected $rules = [
        'photo' => ['nullable', 'string', 'max:255'],
        'image' => ['nullable', 'sometimes', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:1024'],
    ];

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'photo' => __('person.photo'),
            'image' => __('person.photo'),
        ];
    }
}
