<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated user can create a person', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    Livewire::actingAs($user)
        ->test('people.add.person')
        ->set('form.firstname', 'John')
        ->set('form.surname', 'Doe')
        ->set('form.sex', 'm')
        ->set('form.dob', '1980-01-01')
        ->call('savePerson')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('people', [
        'firstname' => 'John',
        'surname'   => 'Doe',
        'sex'       => 'm',
        'dob'       => '1980-01-01',
        'team_id'   => $user->currentTeam->id,
    ]);
});

test('validation errors when required fields are missing', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    Livewire::actingAs($user)
        ->test('people.add.person')
        ->set('form.surname', '')
        ->set('form.sex', '')
        ->call('savePerson')
        ->assertHasErrors(['form.surname' => 'required', 'form.sex' => 'required']);
});
