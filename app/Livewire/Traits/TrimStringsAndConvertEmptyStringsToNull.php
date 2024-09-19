<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

trait TrimStringsAndConvertEmptyStringsToNull
{
    // -----------------------------------------------------------------------
    // ONLY needed in Livewire forms, NOT in blade forms
    // -----------------------------------------------------------------------
    public function updatedTrimStringsAndConvertEmptyStringsToNull($name, $value)
    {
        if (is_string($value)) {
            $trimmed = trim($value);

            data_set($this, $name, $trimmed === '' ? null : $trimmed);
        }
    }
}
