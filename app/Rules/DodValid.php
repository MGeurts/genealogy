<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
use Throwable;

final class DodValid implements DataAwareRule, ValidationRule
{
    /**
     * The full validation data.
     *
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Set the validation data.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Validate the date of death (dod) against other fields.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // No value to validate
        }

        try {
            $dod     = Carbon::parse($value);
            $dodYear = $dod->year;
        } catch (Throwable) {
            return; // Ignore parse error (assume handled elsewhere)
        }

        // Check if 'yod' is provided and matches dod year
        if (! empty($this->data['yod'])) {
            $yod = (int) $this->data['yod'];

            if ($dodYear !== $yod) {
                $fail(__('person.dod_not_matching_yod', ['value' => $yod]));
            }
        }

        if (! empty($this->data['person'])) {
            // Check if dod is after dob
            if (! empty($this->data['person']['dob'])) {
                try {
                    $dob = Carbon::parse($this->data['person']['dob']);

                    if ($dod->lt($dob)) {
                        $fail(__('person.dod_before_dob', ['value' => $this->data['person']['dob']]));
                    }
                } catch (Throwable) {
                    return; // Ignore parse error (assume handled elsewhere)
                }
            }

            // Check if dod year is before yod
            if (! empty($this->data['person']['yob'])) {
                $yob = (int) $this->data['person']['yob'];

                if ($dodYear < $yob) {
                    $fail(__('person.dod_before_yob', ['value' => $this->data['person']['yob']]));
                }
            }
        }
    }
}
