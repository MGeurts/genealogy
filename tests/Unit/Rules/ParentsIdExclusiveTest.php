<?php

declare(strict_types=1);

use App\Rules\ParentsIdExclusive;
use Illuminate\Support\Facades\Validator;

uses(Tests\TestCase::class); // ✅ This makes Laravel boot properly

it('passes when only parents_id is set', function () {
    $data = [
        'father_id'  => null,
        'mother_id'  => null,
        'parents_id' => 1,
    ];

    $rules = [
        'father_id'  => ['nullable', 'integer'],
        'mother_id'  => ['nullable', 'integer'],
        'parents_id' => [
            'nullable',
            'integer',
            new ParentsIdExclusive($data['father_id'], $data['mother_id']),
        ],
    ];

    $validator = Validator::make($data, $rules);

    expect($validator->passes())->toBeTrue();
});

it('passes when father_id and mother_id are set but parents_id is empty', function () {
    $data = [
        'father_id'  => 2,
        'mother_id'  => 3,
        'parents_id' => null,
    ];

    $rules = [
        'father_id'  => ['nullable', 'integer'],
        'mother_id'  => ['nullable', 'integer'],
        'parents_id' => [
            'nullable',
            'integer',
            new ParentsIdExclusive($data['father_id'], $data['mother_id']),
        ],
    ];

    $validator = Validator::make($data, $rules);

    expect($validator->passes())->toBeTrue();
});

it('fails when parents_id is set and father_id is set', function () {
    $data = [
        'father_id'  => 2,
        'mother_id'  => null,
        'parents_id' => 1,
    ];

    $rules = [
        'father_id'  => ['nullable', 'integer'],
        'mother_id'  => ['nullable', 'integer'],
        'parents_id' => [
            'nullable',
            'integer',
            new ParentsIdExclusive($data['father_id'], $data['mother_id']),
        ],
    ];

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeTrue();
});

it('fails when parents_id is set and mother_id is set', function () {
    $data = [
        'father_id'  => null,
        'mother_id'  => 3,
        'parents_id' => 1,
    ];

    $rules = [
        'father_id'  => ['nullable', 'integer'],
        'mother_id'  => ['nullable', 'integer'],
        'parents_id' => [
            'nullable',
            'integer',
            new ParentsIdExclusive($data['father_id'], $data['mother_id']),
        ],
    ];

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeTrue();
});

it('fails when parents_id is set and both father_id and mother_id are set', function () {
    $data = [
        'father_id'  => 2,
        'mother_id'  => 3,
        'parents_id' => 1,
    ];

    $rules = [
        'father_id'  => ['nullable', 'integer'],
        'mother_id'  => ['nullable', 'integer'],
        'parents_id' => [
            'nullable',
            'integer',
            new ParentsIdExclusive($data['father_id'], $data['mother_id']),
        ],
    ];

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeTrue();
});
