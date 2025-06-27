<?php

declare(strict_types=1);

use App\Livewire\People\Edit\Partner;
use App\Models\Couple;
use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('does not detect self-overlap when editing a couple', function (): void {
    // Create user and team, and assign the team to the user
    $user = User::factory()->create();
    $team = Team::factory()->create([
        'user_id'       => $user->id,
        'name'          => 'Test Team',
        'personal_team' => true,
    ]);
    $user->teams()->attach($team);
    $user->forceFill(['current_team_id' => $team->id])->save();

    $this->actingAs($user);

    // Create a person and their partner
    $person  = Person::factory()->create(['yob' => 1980]);
    $partner = Person::factory()->create(['yob' => 1982]);

    // Create a couple with a known date range
    $couple = Couple::factory()->create([
        'person1_id' => $person->id,
        'person2_id' => $partner->id,
        'date_start' => '2005-01-01',
        'date_end'   => '2015-01-01',
        'is_married' => true,
        'has_ended'  => true,
    ]);

    // Load the Livewire component to simulate editing this couple
    Livewire::test(Partner::class, [
        'person' => $person,
        'couple' => $couple,
    ])
        // Set same values as original
        ->set('person2_id', $partner->id)
        ->set('date_start', '2005-01-01')
        ->set('date_end', '2015-01-01')
        ->set('is_married', true)
        ->set('has_ended', true)
        ->call('savePartner')
        ->assertHasNoErrors()
        ->assertRedirect('/people/' . $person->id);

    // Assert update was saved correctly
    $this->assertDatabaseHas('couples', [
        'id'         => $couple->id,
        'date_start' => '2005-01-01',
        'date_end'   => '2015-01-01',
    ]);
});
