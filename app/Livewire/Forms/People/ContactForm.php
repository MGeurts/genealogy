<?php

declare(strict_types=1);

namespace App\Livewire\Forms\People;

use App\Countries;
use Livewire\Form;

final class ContactForm extends Form
{
    // -----------------------------------------------------------------------
    public $street;

    public $number;

    public $postal_code;

    public $city;

    public $province;

    public $state;

    public $country;

    public $phone;

    // -----------------------------------------------------------------------
    public function countries(): \Illuminate\Support\Collection
    {
        return (new Countries(app()->getLocale()))->getAllCountries();
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
