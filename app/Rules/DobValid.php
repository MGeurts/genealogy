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
        if ($this->data['yob']) {
            // dob->year must match yob
            if ($this->data['yob'] !== date('Y', strtotime((string) $value))) {
                $fail(__('person.dob_not_matching_yob', ['value' => $this->data['yob']]));
            }
        } elseif (isset($this->data['person'])) {
            if ($this->data['person']['dod']) {
                // dob can not be after dod
                if ($value > $this->data['person']['dod']) {
                    $fail(__('person.dob_after_dod', ['value' => $this->data['person']['dod']]));
                }
            } elseif ($this->data['person']['yob']) {
                // dob can not be after yod
                if (date('Y', strtotime((string) $value)) > $this->data['person']['yod']) {
                    $fail(__('person.dob_after_yod', ['value' => $this->data['person']['yod']]));
                }
            }
        }
    }
}
