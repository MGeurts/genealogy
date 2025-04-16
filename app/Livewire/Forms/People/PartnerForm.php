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

final class PartnerForm extends Form
{
    // -----------------------------------------------------------------------
    public $person;

    // -----------------------------------------------------------------------
    public $firstname;

    public $surname;

    public $birthname;

    public $nickname;

    public $sex;

    public $gender_id;

    #[Validate]
    public $yob;

    #[Validate]
    public $dob;

    public $pob;

    public $photo;

    // -----------------------------------------------------------------------
    public $person2_id;

    public $date_start;

    public $date_end;

    public $is_married = false;

    public $has_ended = false;

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function genders(): Collection
    {
        return Gender::select('id', 'name')->orderBy('name')->get();
    }

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return $rules = [
            'firstname' => ['nullable', 'string', 'max:255'],
            'surname'   => ['nullable', 'string', 'max:255', 'required_without:person2_id', 'required_with:sex'],
            'birthname' => ['nullable', 'string', 'max:255'],
            'nickname'  => ['nullable', 'string', 'max:255'],

            'sex'       => ['nullable', 'in:m,f', 'required_without:person2_id', 'required_with:surname'],
            'gender_id' => ['nullable', 'integer'],

            'yob' => [
                'nullable',
                'integer',
                'min:1',
                'max:' . date('Y'),
                new YobValid,
            ],
            'dob' => [
                'nullable',
                'date_format:Y-m-d',
                'before_or_equal:today',
                new DobValid,
            ],
            'pob' => ['nullable', 'string', 'max:255'],

            'photo' => ['nullable', 'string', 'max:255'],

            // -----------------------------------------------------------------------
            'person2_id' => ['nullable', 'integer', 'required_without_all:surname, sex', 'exists:people,id'],
            'date_start' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'before:date_end'],
            'date_end'   => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'after:date_start'],
            'is_married' => ['nullable', 'boolean'],
            'has_ended'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'surname.required_without'   => __('validation.surname.required_without'),
            'person_id.required_without' => __('validation.person_id.required_without'),
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'firstname' => __('person.firstname'),
            'surname'   => __('person.surname'),
            'birthname' => __('person.birthname'),
            'nickname'  => __('person.nickname'),

            'sex'       => __('person.sex'),
            'gender_id' => __('person.gender'),

            'yob' => __('person.yob'),
            'dob' => __('person.dob'),
            'pob' => __('person.pob'),

            'photo' => __('person.photo'),

            'person2_id' => __('couple.partner'),
            'date_start' => __('couple.date_start'),
            'date_end'   => __('couple.date_end'),
            'is_married' => __('couple.is_married'),
            'has_ended'  => __('couple.has_ended'),
        ];
    }
}
