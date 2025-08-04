<?php

declare(strict_types=1);

use App\Facades\Person;
use App\Models\Person as P;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it returns people with upcoming birthdays', function () {
    $p1 = P::factory()->create(['dob' => now()->addMonths(3)]);
    $p2 = P::factory()->create(['dob' => now()->addMonths(2)]);
    $p3 = P::factory()->create(['dob' => now()->addMonths(1)]);
    $p4 = P::factory()->create(['dob' => now()->addMonths(4)]);

    expect($people = Person::upcomingBirthdayPeople(2))->toHaveCount(2);
    expect($people->pluck('id')->toArray())->toBe([$p3->id, $p2->id]);
});
