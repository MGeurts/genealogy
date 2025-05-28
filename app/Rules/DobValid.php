<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

final class DobValid implements DataAwareRule, ValidationRule
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
        $dobTimestamp = strtotime((string) $value);

        if (isset($this->data['yob'])) {
            // dob->year must match yob
            if ((int) $this->data['yob'] !== (int) date('Y', $dobTimestamp)) {
                $fail(__('person.dob_not_matching_yob', ['value' => $this->data['yob']]));
            }
        } elseif (isset($this->data['person'])) {
            $person = $this->data['person'];

            if (! empty($person['dod'])) {
                // dob must not be after dod
                $dodTimestamp = strtotime((string) $person['dod']);
                if ($dobTimestamp > $dodTimestamp) {
                    $fail(__('person.dob_after_dod', ['value' => $person['dod']]));
                }
            } elseif (! empty($person['yod'])) {
                // dob->year must not be after yod
                if ((int) date('Y', $dobTimestamp) > (int) $person['yod']) {
                    $fail(__('person.dob_after_yod', ['value' => $person['yod']]));
                }
            }
        }
    }
}
