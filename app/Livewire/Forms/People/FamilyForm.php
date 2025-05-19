<?php

declare(strict_types=1);

namespace App\Livewire\Forms\People;

use Livewire\Form;

final class FamilyForm extends Form
{
    // -----------------------------------------------------------------------
    public $father_id = null;

    public $mother_id = null;

    public $parents_id = null;

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return $rules = [
            'father_id'  => ['nullable', 'integer'],
            'mother_id'  => ['nullable', 'integer'],
            'parents_id' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [
            'father_id'  => __('person.father'),
            'mother_id'  => __('person.mother'),
            'parents_id' => __('parents.father'),
        ];
    }
}
