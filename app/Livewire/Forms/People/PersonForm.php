<?php

declare(strict_types=1);

namespace App\Livewire\Forms\People;

use App\Models\Gender;
use App\Rules\DobValid;
use App\Rules\YobValid;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class PersonForm extends Form
{
    // -----------------------------------------------------------------------
    // New person fields
    // -----------------------------------------------------------------------
    public ?string $firstname = null;

    public ?string $surname = null;

    public ?string $birthname = null;

    public ?string $nickname = null;

    public ?string $sex = null;

    public ?string $gender_id = null;

    #[Validate]
    public ?string $yob = null;

    #[Validate]
    public ?string $dob = null;

    public ?string $pob = null;

    // -----------------------------------------------------------------------
    // Photo uploads (handled by HandlesPhotoUploads trait in components)
    // -----------------------------------------------------------------------
    /** @var array<int, mixed> */
    public array $uploads = [];

    /** @var array<int, mixed> */
    public array $backup = [];

    // -----------------------------------------------------------------------
    // Existing person fields
    // -----------------------------------------------------------------------
    public ?int $person_id = null;

    // -----------------------------------------------------------------------
    /**
     * @return Collection<int, Gender>
     */
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function genders(): Collection
    {
        return Gender::select(['id', 'name'])->orderBy('name')->get();
    }

    // -----------------------------------------------------------------------
    // Validation rules without photo uploads (handled in trait)
    // -----------------------------------------------------------------------
    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'firstname' => ['nullable', 'string', 'max:255'],
            'surname'   => ['nullable', 'string', 'max:255', 'required_without:person_id'],
            'birthname' => ['nullable', 'string', 'max:255'],
            'nickname'  => ['nullable', 'string', 'max:255'],
            'sex'       => ['nullable', 'string', 'max:1', 'in:m,f', 'required_without:person_id'],
            'gender_id' => ['nullable', 'integer'],
            'yob'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y'), new YobValid],
            'dob'       => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', new DobValid],
            'pob'       => ['nullable', 'string', 'max:255'],

            'person_id' => ['nullable', 'integer', 'exists:people,id', 'required_without:surname'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'surname.required_without' => __('validation.surname.required_without'),
            'sex.required_without'     => __('validation.sex.required_without'),

            'person_id.required_without' => __('validation.person_id.required_without'),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'firstname' => __('person.firstname'),
            'surname'   => __('person.surname'),
            'birthname' => __('person.birthname'),
            'nickname'  => __('person.nickname'),
            'sex'       => __('person.sex'),
            'gender_id' => __('person.gender'),
            'yob'       => __('person.yob'),
            'dob'       => __('person.dob'),
            'pob'       => __('person.pob'),

            'person_id' => __('person.person'),
        ];
    }
}
