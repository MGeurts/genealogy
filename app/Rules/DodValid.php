<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class DodValid implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

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
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->data['yod']) {
            // dod->year must match yod
            if ($this->data['yod'] != date('Y', strtotime($value))) {
                $fail(__('person.dod_not_matching_yod', ['value' => $this->data['yod']]));
            }
        } elseif ($this->data['person']['dob']) {
            // dod can not be before dob
            if ($value < $this->data['person']['dob']) {
                $fail(__('person.dod_before_dob', ['value' => $this->data['person']['dob']]));
            }
        } elseif ($this->data['person']['yob']) {
            // dod can not be before yob
            if (date('Y', strtotime($value)) < $this->data['person']['yob']) {
                $fail(__('person.dod_before_yob', ['value' => $this->data['person']['yob']]));
            }
        }
    }
}
