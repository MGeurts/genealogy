<?php

declare(strict_types=1);

use App\Facades\MediaLibrary;
use App\Livewire\People\Add\Mother;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->withPersonalTeam()->create();
    $this->actingAs($user);
});

it('adds photos to mother', function (): void {
    $person = Person::factory()->create();
    $photo1 = UploadedFile::fake()->image('photo1.jpg');
    $photo2 = UploadedFile::fake()->image('photo2.jpg');

    MediaLibrary::shouldReceive('savePhotosToPerson')
        ->once()
        ->andReturn(2);

    livewire(Mother::class, [
        'person' => $person,
    ])
        ->set('form.firstname', 'Jane')
        ->set('form.surname', 'Doe')
        ->set('form.sex', 'f')
        ->set('form.uploads', [$photo1, $photo2])
        ->call('saveMother')
        ->assertHasNoErrors();
});
