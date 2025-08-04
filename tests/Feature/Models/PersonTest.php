<?php

declare(strict_types=1);

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns name', function () {
    $person = Person::factory()->create([
        'firstname' => ' John',
        'surname'   => 'Smith ',
    ]);

    expect($person->name)->toBe('John Smith');
});
