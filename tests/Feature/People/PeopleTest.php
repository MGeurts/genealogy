<?php

declare(strict_types=1);
use App\Models\Person;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('a person can be created', function (): void {
    $person = Person::factory()->create();

    $this->assertDatabaseHas('people', [
        'id' => $person->id,
    ]);
});

test('a person can be updated', function (): void {
    $person = Person::factory()->create();

    $person->update([
        'firstname' => 'Updated',
    ]);

    $this->assertDatabaseHas('people', [
        'id'        => $person->id,
        'firstname' => 'Updated',
    ]);
});

test('a person can be soft deleted', function (): void {
    $person = Person::factory()->create();

    $person->delete();

    $this->assertSoftDeleted($person);
});

test('a person can be hard deleted', function (): void {
    $person = Person::factory()->create();

    $person->forceDelete();

    $this->assertDatabaseMissing('people', [
        'id' => $person->id,
    ]);
});
