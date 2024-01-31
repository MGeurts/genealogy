<?php

namespace App\Livewire\Forms\People;

use Livewire\Form;

class PartnerForm extends Form
{
    // -----------------------------------------------------------------------
    public $person2_id = null;

    public $date_start = null;

    public $date_end = null;

    public $is_married = false;

    public $has_ended = false;

    // -----------------------------------------------------------------------
    protected $rules = [
        'person2_id' => ['required', 'integer'],
        'date_start' => ['nullable', 'date', 'date_format:Y-m-d', 'before_or_equal:today', 'before:date_end'],
        'date_end' => ['nullable', 'date', 'date_format:Y-m-d', 'before_or_equal:today', 'after:date_start'],
        'is_married' => ['required', 'boolean'],
        'has_ended' => ['required', 'boolean'],
    ];

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'person2_id' => __('couple.partner'),
            'date_start' => __('couple.date_start'),
            'date_end' => __('couple.date_end'),
            'is_married' => __('couple.is_married'),
            'has_ended' => __('couple.has_ended'),
        ];
    }
}
