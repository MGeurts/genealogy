<?php

declare(strict_types=1);
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('email verification screen can be rendered', function () {
    if (! Features::enabled(Features::emailVerification())) {
        $this->markTestSkipped('Email verification not enabled.');
    }

    $user = User::factory()->withPersonalTeam()->unverified()->create();

    $response = $this->actingAs($user)->get('/email/verify');

    $response->assertStatus(200);
});
test('email can be verified', function () {
    if (! Features::enabled(Features::emailVerification())) {
        $this->markTestSkipped('Email verification not enabled.');
    }

    Event::fake();

    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('people.search', absolute: false) . '?verified=1');
});
test('email can not verified with invalid hash', function () {
    if (! Features::enabled(Features::emailVerification())) {
        $this->markTestSkipped('Email verification not enabled.');
    }

    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
