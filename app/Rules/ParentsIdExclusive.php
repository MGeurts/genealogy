<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ParentsIdExclusive implements ValidationRule
{
    protected $fatherId;

    protected $motherId;

    public function __construct($fatherId, $motherId)
    {
        $this->fatherId = $fatherId;
        $this->motherId = $motherId;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, string): void  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value && ($this->fatherId || $this->motherId)) {
            $fail(__('person.parents_id_exclusive'));
        }
    }
}
