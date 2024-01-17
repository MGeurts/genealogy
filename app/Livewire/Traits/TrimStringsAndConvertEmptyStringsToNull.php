<?php

namespace App\Livewire\Traits;

trait TrimStringsAndConvertEmptyStringsToNull
{
    public function updatedTrimStringsAndConvertEmptyStringsToNull($name, $value)
    {
        if (is_string($value)) {
            $trimmed = trim($value);

            data_set($this, $name, $trimmed === '' ? null : $trimmed);
        }
    }
}
