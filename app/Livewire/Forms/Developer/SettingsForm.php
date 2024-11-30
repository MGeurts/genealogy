<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Developer;

use Livewire\Form;

class SettingsForm extends Form
{
    // -----------------------------------------------------------------------
    public bool $logAllQueries = false;

    public bool $logAllQueriesSlow = false;

    public bool $logAllQueriesNPlusOne = false;

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return $rules = [];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [];
    }
}
