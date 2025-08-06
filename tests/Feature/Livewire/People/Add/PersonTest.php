<?php

declare(strict_types=1);

use App\Facades\MediaLibrary;
use App\Livewire\People\Add\Person;
use App\Livewire\People\Add\Person as AddPerson;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated user can create a person', function (): void {
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

test('validation errors when required fields are missing', function (): void {
    $user = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user);

    Livewire::actingAs($user)
        ->test(AddPerson::class)
        ->set('form.surname', '')
        ->set('form.sex', '')
        ->call('savePerson')
        ->assertHasErrors(['form.surname' => 'required', 'form.sex' => 'required']);
});

it('adds photos to person', function (): void {
    $user   = User::factory()->withPersonalTeam()->create();
    $photo1 = UploadedFile::fake()->image('photo1.jpg');
    $photo2 = UploadedFile::fake()->image('photo2.jpg');

    $this->actingAs($user);

    MediaLibrary::shouldReceive('savePhotosToPerson')
        ->once()
        ->andReturn(2);

    \Pest\Livewire\livewire(Person::class)
        ->set('form.firstname', 'Jane')
        ->set('form.surname', 'Doe')
        ->set('form.sex', 'f')
        ->set('form.uploads', [$photo1, $photo2])
        ->call('savePerson')
        ->assertHasNoErrors();
});
