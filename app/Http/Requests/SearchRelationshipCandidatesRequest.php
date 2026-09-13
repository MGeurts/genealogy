<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the compact query payload sent by the remote relationship selector.
 *
 * TallStackUI serializes the current selection as JSON for a GET request; this
 * request normalizes that value before validation so callers can only request
 * one already-selected person alongside a search term.
 */
class SearchRelationshipCandidatesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'search'     => ['nullable', 'string', 'max:100'],
            'selected'   => ['nullable', 'array', 'max:1'],
            'selected.*' => ['integer', 'distinct'],
        ];
    }

    /** @return list<int> */
    public function selectedPersonIds(): array
    {
        return array_map('intval', $this->validated('selected', []));
    }

    protected function prepareForValidation(): void
    {
        $selected = $this->input('selected', []);

        if (is_string($selected)) {
            $selected = json_decode($selected, true);
        }

        $this->merge([
            'selected' => is_array($selected) ? $selected : ['invalid'],
        ]);
    }
}
