<?php

namespace App\Livewire\Forms\People;

use Livewire\Form;

class DeathForm extends Form
{
    // -----------------------------------------------------------------------
    public $yod;

    public $dod;

    public $pod;

    public $cemetery_location_name;

    public $cemetery_location_address;

    public $cemetery_location_latitude;

    public $cemetery_location_longitude;

    // -----------------------------------------------------------------------
    // ToDo : add to rules : yod must be equal to dod->format("Y) when dod not null
    protected $rules = [
        'yod' => ['nullable', 'date_format:Y'],
        'dod' => ['nullable', 'date', 'date_format:Y-m-d', 'before_or_equal:today'],
        'pod' => ['nullable', 'string', 'max:255'],
        'cemetery_location_name' => ['nullable', 'string', 'max:255'],
        'cemetery_location_address' => ['nullable', 'string', 'max:255'],
        'cemetery_location_latitude' => ['required_with:cemetery_location_longitude', 'nullable', 'numeric', 'decimal:0,13', 'min:-90', 'max:90'],
        'cemetery_location_longitude' => ['required_with:cemetery_location_latitude', 'nullable', 'numeric', 'decimal:0,13', 'min:-180', 'max:180'],
    ];

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

    // -----------------------------------------------------------------------
    public function YodCorrespondsDod()
    {
        return $this->yod && $this->dod ? $this->yod == date('Y', strtotime($this->dod)) : true;
    }
}
