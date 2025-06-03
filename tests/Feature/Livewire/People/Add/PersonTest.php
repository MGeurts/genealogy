<?php

declare(strict_types=1);
use App\Livewire\People\Add\Person as AddPerson;
use App\Models\User;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated user can create a person', function () {
    $user = User::factory()->withPersonalTeam()->create();

    Livewire::actingAs($user)
        ->test(AddPerson::class)
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
test('validation errors when required fields are missing', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    Livewire::actingAs($user)
        ->test(AddPerson::class)
        ->set('form.surname', '')
        ->set('form.sex', '')
        ->call('savePerson')
        ->assertHasErrors(['form.surname' => 'required', 'form.sex' => 'required']);
});
