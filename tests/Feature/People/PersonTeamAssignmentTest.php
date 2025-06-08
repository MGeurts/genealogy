<?php

declare(strict_types=1);
use App\Models\Person;
use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('creates a person assigned to the users current team', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    $person = Person::factory()
        ->withUser($user)
        ->create();

    $this->assertDatabaseHas('people', [
        'id'      => $person->id,
        'team_id' => $user->currentTeam->id,
    ]);
});
