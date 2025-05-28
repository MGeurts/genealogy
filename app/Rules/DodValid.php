<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

final class DodValid implements DataAwareRule, ValidationRule
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
        if (! empty($this->data['yod'])) {
            // dod->year must match yod
            if ((int) $this->data['yod'] !== (int) date('Y', strtotime((string) $value))) {
                $fail(__('person.dod_not_matching_yod', ['value' => $this->data['yod']]));
            }
        } elseif (! empty($this->data['person'])) {
            $person = $this->data['person'];

            if (! empty($person['dob'])) {
                // dod cannot be before dob
                if (strtotime((string) $value) < strtotime((string) $person['dob'])) {
                    $fail(__('person.dod_before_dob', ['value' => $person['dob']]));
                }
            } elseif (! empty($person['yob'])) {
                // dod->year cannot be before yob
                if ((int) date('Y', strtotime((string) $value)) < (int) $person['yob']) {
                    $fail(__('person.dod_before_yob', ['value' => $person['yob']]));
                }
            }
        }
    }
}
