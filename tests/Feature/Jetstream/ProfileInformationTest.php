<?php

declare(strict_types=1);
use App\Models\User;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('current profile information is available', function (): void {
    $this->actingAs($user = User::factory()->create());

    $component = Livewire::test(UpdateProfileInformationForm::class);

    expect($component->state['surname'])->toEqual($user->surname);
    expect($component->state['email'])->toEqual($user->email);
});

test('profile information can be updated', function (): void {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(UpdateProfileInformationForm::class)
        ->set('state', [
            'surname'  => 'Test Name',
            'email'    => 'test@example.com',
            'language' => 'en',
            'timezone' => 'UTC',
        ])
        ->call('updateProfileInformation');

    expect($user->fresh()->surname)->toEqual('Test Name');
    expect($user->fresh()->email)->toEqual('test@example.com');
});
