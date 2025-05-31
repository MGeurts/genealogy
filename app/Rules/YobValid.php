<?php

declare(strict_types=1);

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Throwable;

final class YobValid implements DataAwareRule, ValidationRule
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

        $yob = (int) $value;

        if (! empty($this->data['dob'])) {
            try {
                $dob = Carbon::parse($this->data['dob']);

                if ($yob !== $dob->year) {
                    $fail(__('person.yob_not_matching_dob', ['value' => $dob->year]));
                }
            } catch (Throwable) {
                return; // Ignore parse error (assume handled elsewhere)
            }
        }

        if (! empty($this->data['person'])) {
            // Check if year of birth is equal or before date of death
            if (! empty($this->data['person']['dod'])) {
                try {
                    $dod = Carbon::parse($this->data['person']['dod']);

                    if ($yob >= $dod->year) {
                        $fail(__('person.yob_after_dod', ['value' => $this->data['person']['dod']]));
                    }
                } catch (Throwable) {
                    return; // Ignore parse error (assume handled elsewhere)
                }
            }

            // Check if year of birth is equal or before year of death
            if (! empty($this->data['person']['yod'])) {
                $yod = (int) $this->data['person']['yod'];

                if ($yob >= $yod) {
                    $fail(__('person.yob_after_yod', ['value' => $yod]));
                }
            }
        }
    }
}
