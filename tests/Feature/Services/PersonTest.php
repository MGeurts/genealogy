<?php

declare(strict_types=1);

use App\Facades\Person;
use App\Models\Person as P;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it returns people with upcoming birthdays', function () {
    $p1 = P::factory()->create(['dob' => now()->subYear()->addMonths(3)]);
    $p2 = P::factory()->create(['dob' => now()->subYear()->addMonths(2)]);
    $p3 = P::factory()->create(['dob' => now()->subYear()->addMonths(1)]);
    $p4 = P::factory()->create(['dob' => now()->subYear()->addMonths(4)]);
    $p5 = P::factory()->create(['dob' => today()->subYear()]);

    expect($people = Person::upcomingBirthdayPeople(2))->toHaveCount(3);
    expect($people->pluck('id')->toArray())->toBe([$p5->id, $p3->id, $p2->id]);
});
