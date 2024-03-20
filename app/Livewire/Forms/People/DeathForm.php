<?php

namespace App\Livewire\Forms\People;

use App\Rules\DodValid;
use App\Rules\YodValid;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DeathForm extends Form
{
    // -----------------------------------------------------------------------
    public $person;

    // -----------------------------------------------------------------------
    #[Validate]
    public $yod = null;

    #[Validate]
    public $dod = null;

    public $pod = null;

    public $cemetery_location_name = null;

    public $cemetery_location_address = null;

    public $cemetery_location_latitude = null;

    public $cemetery_location_longitude = null;

    public function rules()
    {
        return [
            'yod' => [
                'nullable',
                'date_format:Y',
                // new YodValid, To Do : not working
            ],
            'dod' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:today',
                // new DodValid, To Do : not working
            ],
            'pod' => ['nullable', 'string', 'max:255'],
            'cemetery_location_name' => ['nullable', 'string', 'max:255'],
            'cemetery_location_address' => ['nullable', 'string', 'max:255'],
            'cemetery_location_latitude' => ['required_with:cemetery_location_longitude', 'nullable', 'numeric', 'decimal:0,13', 'min:-90', 'max:90'],
            'cemetery_location_longitude' => ['required_with:cemetery_location_latitude', 'nullable', 'numeric', 'decimal:0,13', 'min:-180', 'max:180'],
        ];
    }

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'yod' => __('person.yod'),
            'dod' => __('person.dod'),
            'pod' => __('person.pod'),
            'cemetery_location_name' => __('metadata.location_name'),
            'cemetery_location_address' => __('metadata.address'),
            'cemetery_location_latitude' => __('metadata.latitude'),
            'cemetery_location_longitude' => __('metadata.longitude'),
        ];
    }
}
