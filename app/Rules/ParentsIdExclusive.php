<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ParentsIdExclusive implements ValidationRule
{
    public function __construct(private ?int $fatherId, private ?int $motherId) {}

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
