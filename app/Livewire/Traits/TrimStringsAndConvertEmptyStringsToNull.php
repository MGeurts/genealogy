<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

trait TrimStringsAndConvertEmptyStringsToNull
{
    // -----------------------------------------------------------------------
    // ONLY needed in Livewire forms, NOT in blade forms
    // -----------------------------------------------------------------------
    public function updatedTrimStringsAndConvertEmptyStringsToNull($name, $value): void
    {
        if (is_string($value)) {
            $trimmed = mb_trim($value);

            data_set($this, $name, $trimmed === '' ? null : $trimmed);
        }
    }
}
