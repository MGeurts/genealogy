<?php

declare(strict_types=1);
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    if (! Features::enabled(Features::registration())) {
        $this->markTestSkipped('Registration support is not enabled.');
    }

    $response = $this->get('/register');

    $response->assertStatus(200);
});
test('registration screen cannot be rendered if support is disabled', function () {
    if (Features::enabled(Features::registration())) {
        $this->markTestSkipped('Registration support is enabled.');
    }

    $response = $this->get('/register');

    $response->assertStatus(404);
});
test('new users can register', function () {
    if (! Features::enabled(Features::registration())) {
        $this->markTestSkipped('Registration support is not enabled.');
    }

    $response = $this->post('/register', [
        'surname'               => 'Test User',
        'email'                 => 'test@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
        'language'              => 'en',
        'timezone'              => 'UTC',
        'terms'                 => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('people.search', absolute: false));
});
