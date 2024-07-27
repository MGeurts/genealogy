<?php

declare(strict_types=1);

namespace App\Livewire\Forms\People;

use App\Countries;
use Livewire\Attributes\Computed;
use Livewire\Form;

class ContactForm extends Form
{
    // -----------------------------------------------------------------------
    public $street = null;

    public $number = null;

    public $postal_code = null;

    public $city = null;

    public $province = null;

    public $state = null;

    public $country = null;

    public $phone = null;

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function countries()
    {
        $countries = new Countries(app()->getLocale());

        return $countries->all();
    }

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return [
            'street'      => ['nullable', 'string', 'max:100'],
            'number'      => ['nullable', 'string', 'max:20'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city'        => ['nullable', 'string', 'max:100'],
            'province'    => ['nullable', 'string', 'max:100'],
            'state'       => ['nullable', 'string', 'max:100'],
            'country'     => ['nullable', 'string', 'max:2'],
            'phone'       => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [
            'street'      => __('person.street'),
            'number'      => __('person.number'),
            'postal_code' => __('person.postal_code'),
            'city'        => __('person.city'),
            'province'    => __('person.province'),
            'state'       => __('person.state'),
            'country'     => __('person.country'),
            'phone'       => __('person.phone'),
        ];
    }
}
