<?php

declare(strict_types=1);
use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('api tokens can be created', function (): void {
    if (! Features::hasApiFeatures()) {
        $this->markTestSkipped('API support is not enabled.');
    }

    if (env('PARALLEL_TESTING') === true || env('PARALLEL_TESTING') === '1') {
        $this->markTestSkipped('Skipping in parallel test environment.');
    }

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(ApiTokenManager::class)
        ->set(['createApiTokenForm' => [
            'name'        => 'Test Token',
            'permissions' => [
                'read',
                'update',
            ],
        ]])
        ->call('createApiToken');

    expect($user->fresh()->tokens)->toHaveCount(1);
    expect($user->fresh()->tokens->first()->name)->toEqual('Test Token');
    expect($user->fresh()->tokens->first()->can('read'))->toBeTrue();
    expect($user->fresh()->tokens->first()->can('delete'))->toBeFalse();
});
