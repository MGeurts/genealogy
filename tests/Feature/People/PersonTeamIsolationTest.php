<?php

declare(strict_types=1);

use App\Models\Person;
use App\Models\Team;
use App\Models\User;

test('a user without a team cannot query or resolve another team person', function (): void {
    config(['app.debug' => true]);
    $person = Person::factory()->create();
    $user   = User::factory()->create();

    $this->actingAs($user);

    expect(Person::query()->whereKey($person->id)->exists())->toBeFalse();
    $this->get(route('people.show', $person))->assertNotFound()->assertDontSee($person->firstname);
});

test('an existing user without an active team returns to their personal team', function (): void {
    $user           = User::factory()->withPersonalTeam()->create();
    $personalTeamId = $user->current_team_id;
    $user->forceFill(['current_team_id' => null])->save();

    $this->actingAs($user->fresh());
    $this->get(route('home'))->assertOk();

    expect($user->fresh()->current_team_id)->toBe($personalTeamId);
});

test('team scoped queries allow only the current team', function (): void {
    $user          = User::factory()->withPersonalTeam()->create();
    $ownPerson     = Person::factory()->create(['team_id' => $user->current_team_id]);
    $foreignPerson = Person::factory()->create(['team_id' => Team::factory()->create()->id]);

    $this->actingAs($user);

    expect(Person::query()->whereKey($ownPerson->id)->exists())->toBeTrue()
        ->and(Person::query()->whereKey($foreignPerson->id)->exists())->toBeFalse();
});

test('developers and unauthenticated background queries retain their existing access', function (): void {
    $person = Person::factory()->create();

    expect(Person::query()->whereKey($person->id)->exists())->toBeTrue();

    $this->actingAs(User::factory()->create(['is_developer' => true]));

    expect(Person::query()->whereKey($person->id)->exists())->toBeTrue();
});

test('a user without a team can open the team creation page', function (): void {
    $this->actingAs(User::factory()->create());

    $this->get(route('teams.create'))->assertOk();
});

test('a user without a personal team can recover another accessible team', function (): void {
    $user = User::factory()->create();
    $team = Team::factory()->create(['personal_team' => false]);
    $team->users()->attach($user, ['role' => 'editor']);

    $this->actingAs($user);
    $this->get(route('home'))->assertOk();

    expect($user->fresh()->current_team_id)->toBe($team->id);
});
