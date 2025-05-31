<?php

declare(strict_types=1);

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Throwable;

final class YodValid implements DataAwareRule, ValidationRule
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

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // No value to validate
        }

        $yod = (int) $value;

        if (! empty($this->data['dod'])) {
            try {
                $dod = Carbon::parse($this->data['dod']);

                if ($yod !== $dod->year) {
                    $fail(__('person.yod_not_matching_dod', ['value' => $this->data['dod']]));
                }
            } catch (Throwable) {
                return; // Ignore parse error (assume handled elsewhere)
            }
        }

        if (! empty($this->data['person'])) {
            // Check if year of death is equal or after date of birth
            if (! empty($this->data['person']['dob'])) {
                try {
                    $dob = Carbon::parse($this->data['person']['dob']);

                    if ($yod < $dob->year) {
                        $fail(__('person.yod_before_dob', ['value' => $this->data['person']['dob']]));
                    }
                } catch (Throwable) {
                    return; // Ignore parse error (assume handled elsewhere)
                }
            }

            // Check if year of death is equal or after year of birth
            if (! empty($this->data['person']['yob'])) {
                $yob = (int) $this->data['person']['yob'];

                if ($yod < $yob) {
                    $fail(__('person.yod_before_yob', ['value' => $yob]));
                }
            }
        }
    }
}
