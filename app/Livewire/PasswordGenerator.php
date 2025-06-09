<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PasswordGenerator extends Component
{
    #[Validate('required|integer|min:6|max:128')]
    public int $length = 20;

    public bool $useNumbers = true;

    public bool $useSymbols = true;

    public string $generatedPassword = '';

    public string $passwordStrength = '';

    public float $passwordEntropy = 0;

    public string $passwordColor = 'red';

    // -----------------------------------------------------------------------
    public function generate(): void
    {
        $this->validateOnly('length');

        // -----------------------------------------------------------------------
        // You could instead use str::password() if you want to use the built-in Laravel function,
        // but this function allows for more customization, such as specifiing the allowed symbols.
        // -----------------------------------------------------------------------
        $this->generatedPassword = $this->password(
            length: $this->length,
            letters: true,
            numbers: $this->useNumbers,
            symbols: $this->useSymbols
        );

        ['strength' => $this->passwordStrength, 'entropy' => $this->passwordEntropy] = $this->calculateStrength($this->generatedPassword);

        $this->passwordColor = match ($this->passwordStrength) {
            'app.password_very_strong' => 'green',
            'app.password_strong'      => 'lime',
            'app.password_moderate'    => 'yellow',
            'app.password_weak'        => 'orange',
            default                    => 'red',
        };
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.password-generator');
    }

    // -----------------------------------------------------------------------
    // Generates a random password based on the specified criteria.
    //
    // @param int $length The desired length of the password.
    // @param bool $letters Whether to include letters in the password.
    // @param bool $numbers Whether to include numbers in the password.
    // @param bool $symbols Whether to include symbols in the password.
    // @param bool $spaces Whether to include spaces in the password.
    //
    // @return string The generated password.
    // @throws \InvalidArgumentException If the length is less than 6 or greater than 128.
    // @throws \Exception If an error occurs during random number generation.
    // -----------------------------------------------------------------------
    private function password($length = 32, $letters = true, $numbers = true, $symbols = true, $spaces = false): string
    {
        $password = new Collection();

        $options = (new Collection([
            'letters' => $letters === true ? [
                'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
                'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
                'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
                'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            ] : null,
            'numbers' => $numbers === true ? [
                '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            ] : null,
            'symbols' => $symbols === true ? [
                '~', '!', '#', '$', '%', '^', '&', '*', '(', ')', '-',
                '_', '.', ',', '<', '>', '?', '/', '\\', '{', '}', '[',
                ']', '|', ':', ';',
            ] : null,
            'spaces' => $spaces === true ? [' '] : null,
        ]))
            ->filter()
            ->each(fn ($c) => $password->push($c[random_int(0, count($c) - 1)]))
            ->flatten();

        $length = $length - $password->count();

        return $password->merge($options->pipe(
            fn ($c) => Collection::times($length, fn () => $c[random_int(0, $c->count() - 1)])
        ))->shuffle()->implode('');
    }

    private function calculateStrength(string $password): array
    {
        $length   = mb_strlen($password);
        $poolSize = 0;

        if (preg_match('/[a-z]/', $password)) {
            $poolSize += 26;
        }
        if (preg_match('/[A-Z]/', $password)) {
            $poolSize += 26;
        }
        if (preg_match('/[0-9]/', $password)) {
            $poolSize += 10;
        }
        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $poolSize += 26;
        }

        if ($poolSize === 0 || $length === 0) {
            return ['strength' => 'app.password_very_weak', 'entropy' => 0];
        }

        $entropy = round($length * log($poolSize, 2), 1);

        $strength = match (true) {
            $entropy >= 100 => 'app.password_very_strong',
            $entropy >= 80  => 'app.password_strong',
            $entropy >= 60  => 'app.password_moderate',
            $entropy >= 40  => 'app.password_weak',
            default         => 'app.password_very_weak',
        };

        return ['strength' => $strength, 'entropy' => $entropy];
    }
}
