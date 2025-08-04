<?php

declare(strict_types=1);

use App\Facades\UserService;
use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user accounts can be deleted', function (): void {
    if (! Features::hasAccountDeletionFeatures()) {
        $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    UserService::shouldReceive('getTeamStatistics')->andReturn(collect());

    $component = Livewire::test(DeleteUserForm::class)
        ->set('password', 'password')
        ->call('deleteUser');

    $user->refresh();

    expect($user->deleted_at)->not->toBeNull();
});

test('correct password must be provided before account can be deleted', function (): void {
    if (! Features::hasAccountDeletionFeatures()) {
        $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    UserService::shouldReceive('getTeamStatistics')->andReturn(collect());

    Livewire::test(DeleteUserForm::class)
        ->set('password', 'wrong-password')
        ->call('deleteUser')
        ->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
