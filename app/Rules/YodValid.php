<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

final class YodValid implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! empty($this->data['dod'])) {
            // yod must match dod->year
            if ((int) $value !== (int) date('Y', strtotime((string) $this->data['dod']))) {
                $fail(__('person.yod_not_matching_dod', ['value' => $this->data['dod']]));
            }
        } elseif (! empty($this->data['person'])) {
            $person = $this->data['person'];

            if (! empty($person['dob'])) {
                // yod may not be before dob->year
                if ((int) $value < (int) date('Y', strtotime((string) $person['dob']))) {
                    $fail(__('person.yod_before_dob', ['value' => $person['dob']]));
                }
            } elseif (! empty($person['yob'])) {
                // yod may not be before yob
                if ((int) $value < (int) $person['yob']) {
                    $fail(__('person.yod_before_yob', ['value' => $person['yob']]));
                }
            }
        }
    }
}
