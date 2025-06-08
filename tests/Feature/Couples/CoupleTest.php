<?php

declare(strict_types=1);

use App\Models\Couple;
use App\Models\Person;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('a couple can be created with two people', function (): void {
    $husband = Person::factory()->create();
    $wife    = Person::factory()->create();

    $couple = Couple::create([
        'person1_id' => $husband->id,
        'person2_id' => $wife->id,
    ]);

    $this->assertDatabaseHas('couples', [
        'id'         => $couple->id,
        'person1_id' => $husband->id,
        'person2_id' => $wife->id,
    ]);
});

test('a couple can be updated', function (): void {
    $husband = Person::factory()->create();
    $wife    = Person::factory()->create();

    $couple = Couple::create([
        'person1_id' => $husband->id,
        'person2_id' => $wife->id,
    ]);

    $couple->update([
        'date_start' => '2023-01-01',
        'is_married' => true,
    ]);

    $this->assertDatabaseHas('couples', [
        'date_start' => '2023-01-01',
        'is_married' => true,
    ]);
});

test('a couple can be deleted', function (): void {
    $husband = Person::factory()->create();
    $wife    = Person::factory()->create();

    $couple = Couple::create([
        'person1_id' => $husband->id,
        'person2_id' => $wife->id,
    ]);

    $couple->delete();

    $this->assertDatabaseMissing('couples', [
        'id' => $couple->id,
    ]);
});
