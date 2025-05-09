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
            'surname'   => ['required', 'string', 'max:255'],
            'birthname' => ['nullable', 'string', 'max:255'],
            'nickname'  => ['nullable', 'string', 'max:255'],

            'sex'       => ['required', 'in:m,f'],
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
        ];
    }

    public function messages(): array
    {
        return [];
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
        ];
    }
}
