<?php

namespace App\Livewire\Forms\People;

use App\Models\Country;
use Livewire\Attributes\Computed;
use Livewire\Form;

class ContactForm extends Form
{
    // -----------------------------------------------------------------------
    public $street;

    public $number;

    public $postal_code;

    public $city;

    public $province;

    public $state;

    public $country_id;

    public $phone;

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function countries()
    {
        return Country::select('id', 'name')->orderBy('name')->get()->toArray();
    }

    // -----------------------------------------------------------------------
    protected $rules = [
        'street' => ['nullable', 'string', 'max:100'],
        'number' => ['nullable', 'string', 'max:20'],
        'postal_code' => ['nullable', 'string', 'max:20'],
        'city' => ['nullable', 'string', 'max:100'],
        'province' => ['nullable', 'string', 'max:100'],
        'state' => ['nullable', 'string', 'max:100'],
        'country_id' => ['nullable', 'integer'],
        'phone' => ['nullable', 'string', 'max:50'],
    ];

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'street' => __('person.street'),
            'number' => __('person.number'),
            'postal_code' => __('person.postal_code'),
            'city' => __('person.city'),
            'province' => __('person.province'),
            'state' => __('person.state'),
            'country_id' => __('person.country'),
            'phone' => __('person.phone'),
        ];
    }
}
