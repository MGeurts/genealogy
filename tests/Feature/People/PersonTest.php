<?php

declare(strict_types=1);

use App\Models\Person;
use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('a person can be created', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $person = Person::factory()
        ->withUser($user)
        ->create();

    $this->assertDatabaseHas('people', [
        'id' => $person->id,
    ]);
});

test('a person can be updated', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $person = Person::factory()
        ->withUser($user)
        ->create();

    $person->update([
        'firstname' => 'Updated',
    ]);

    $this->assertDatabaseHas('people', [
        'id'        => $person->id,
        'firstname' => 'Updated',
    ]);
});

test('a person can be soft deleted', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $person = Person::factory()
        ->withUser($user)
        ->create();

    $person->delete();

    $this->assertSoftDeleted($person);
});

test('a person can be hard deleted', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $person = Person::factory()
        ->withUser($user)
        ->create();

    $person->forceDelete();

    $this->assertDatabaseMissing('people', [
        'id' => $person->id,
    ]);
});

test('returns full name', function (): void {
    $person = Person::factory()->create([
        'firstname' => ' John',
        'surname'   => 'Doe ',
    ]);

    expect($person->name)->toBe('John Doe');
});

test('returns full name even when firstname is missing', function (): void {
    $person = Person::factory()->create([
        'firstname' => null,
        'surname'   => '  Doe ',
    ]);

    expect($person->name)->toBe('Doe');
});
