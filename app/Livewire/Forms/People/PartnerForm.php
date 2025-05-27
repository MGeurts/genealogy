<?php

declare(strict_types=1);

namespace App\Livewire\Forms\People;

use App\Models\Person;
use Livewire\Form;

final class PartnerForm extends Form
{
    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public $person2_id = null;

    public $date_start = null;

    public $date_end = null;

    public $is_married = false;

    public $has_ended = false;

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return $rules = [
            'person2_id' => ['required', 'integer', 'exists:people,id'],
            'date_start' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'before:date_end'],
            'date_end'   => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'after:date_start'],
            'is_married' => ['nullable', 'boolean'],
            'has_ended'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [
            'person2_id' => __('couple.partner'),
            'date_start' => __('couple.date_start'),
            'date_end'   => __('couple.date_end'),
            'is_married' => __('couple.is_married'),
            'has_ended'  => __('couple.has_ended'),
        ];
    }
}
