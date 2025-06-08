<?php

declare(strict_types=1);

use App\Models\Couple;
use App\Models\Person;
use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('a couple can be created with two people', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $husband = Person::factory()->withUser($user)->create();
    $wife    = Person::factory()->withUser($user)->create();

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
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $husband = Person::factory()->withUser($user)->create();
    $wife    = Person::factory()->withUser($user)->create();

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
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $husband = Person::factory()->withUser($user)->create();
    $wife    = Person::factory()->withUser($user)->create();

    $couple = Couple::create([
        'person1_id' => $husband->id,
        'person2_id' => $wife->id,
    ]);

    $couple->delete();

    $this->assertDatabaseMissing('couples', [
        'id' => $couple->id,
    ]);
});
