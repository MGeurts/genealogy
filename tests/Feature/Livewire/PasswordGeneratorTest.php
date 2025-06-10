<?php

declare(strict_types=1);

use App\Livewire\PasswordGenerator;
use Livewire\Livewire;

test('generate a password with default settings', function (): void {
    Livewire::test(PasswordGenerator::class)
        ->call('generate')
        ->assertSet('generatedPassword', fn ($value) => is_string($value) && mb_strlen($value) === 20)
        ->assertSet('passwordStrength', fn ($value) => in_array($value, [
            'app.password_very_strong',
            'app.password_strong',
            'app.password_moderate',
            'app.password_weak',
            'app.password_very_weak',
        ]))
        ->assertSet('passwordEntropy', fn ($value) => is_float($value) && $value > 0)
        ->assertSet('estimatedEntropy', fn ($value) => is_float($value) && $value > 0)
        ->assertSet('shannonEntropy', fn ($value) => is_float($value) && $value > 0)
        ->assertSet('passwordColor', fn ($value) => in_array($value, ['green', 'lime', 'yellow', 'orange', 'red']));
});

test('generate a password with numbers and symbols disabled', function (): void {
    Livewire::test(PasswordGenerator::class)
        ->set('length', 16)
        ->set('useNumbers', false)
        ->set('useSymbols', false)
        ->call('generate')
        ->assertSet('generatedPassword', fn ($value) => mb_strlen($value) === 16 && preg_match('/^[a-zA-Z]+$/', $value) === 1);
});

test('validate password length', function (): void {
    Livewire::test(PasswordGenerator::class)
        ->set('length', 4)
        ->call('generate')
        ->assertHasErrors(['length' => 'min']);

    Livewire::test(PasswordGenerator::class)
        ->set('length', 130)
        ->call('generate')
        ->assertHasErrors(['length' => 'max']);
});
