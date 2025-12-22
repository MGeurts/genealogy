<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ParentsIdExclusive implements ValidationRule
{
    protected mixed $fatherId;

    protected mixed $motherId;

    public function __construct(mixed $fatherId, mixed $motherId)
    {
        $this->fatherId = $fatherId;
        $this->motherId = $motherId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value && ($this->fatherId || $this->motherId)) {
            $fail(__('person.parents_id_exclusive'));
        }
    }
}
