<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
use Throwable;

final class DobValid implements DataAwareRule, ValidationRule
{
    /**
     * The full validation data.
     *
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Set the validation data.
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Validate the date of birth (dob) against other fields.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // No value to validate
        }

        try {
            $dob     = Carbon::parse($value);
            $dobYear = $dob->year;
        } catch (Throwable) {
            return; // Ignore parse error (assume handled elsewhere)
        }

        // Check if 'yob' is provided and matches dob year
        if (! empty($this->data['yob'])) {
            $yob = (int) $this->data['yob'];

            if ($dobYear !== $yob) {
                $fail(__('person.dob_not_matching_yob', ['value' => $yob]));
            }
        }

        if (! empty($this->data['person'])) {
            // Check if dob is before dod
            if (! empty($this->data['person']['dod'])) {
                try {
                    $dod = Carbon::parse($this->data['person']['dod']);

                    if ($dob->gt($dod)) {
                        $fail(__('person.dob_after_dod', ['value' => $this->data['person']['dod']]));
                    }
                } catch (Throwable) {
                    return; // Ignore parse error (assume handled elsewhere)
                }
            }

            // Check if dob year is before yod
            if (! empty($this->data['person']['yod'])) {
                $yod = (int) $this->data['person']['yod'];

                if ($dobYear > $yod) {
                    $fail(__('person.dob_after_yod', ['value' => $this->data['person']['yod']]));
                }
            }
        }
    }
}
