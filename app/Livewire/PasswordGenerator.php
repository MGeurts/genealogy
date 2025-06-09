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
    public int $length = 22;

    public bool $useNumbers = true;

    public bool $useSymbols = true;

    public string $generatedPassword = '';

    public string $passwordStrength = '';

    public float $passwordEntropy = 0;

    public string $passwordColor = 'red';

    // Optional: expose these if needed in the Blade view
    // public float $estimatedEntropy = 0;
    // public float $shannonEntropy = 0;

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
            symbols: $this->useSymbols,
            spaces: false // Spaces are not included by default, but you can add an option for it if needed.
        );

        $entropyData = $this->calculateCombinedEntropy($this->generatedPassword);

        $this->passwordStrength = $entropyData['strength'];
        $this->passwordEntropy  = $entropyData['entropy'];

        // Optional: expose these if needed
        // $this->estimatedEntropy = $entropyData['estimated_entropy'];
        // $this->shannonEntropy   = $entropyData['shannon_entropy'];

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
    protected function validationAttributes(): array
    {
        return [
            'length' => __('app.password_length'),
        ];
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
            ->each(fn ($chars) => $password->push($chars[random_int(0, count($chars) - 1)]))
            ->flatten();

        $length -= $password->count();

        return $password->merge(
            $options->pipe(fn ($chars) => Collection::times($length, fn () => $chars[random_int(0, $chars->count() - 1)]))
        )->shuffle()->implode('');
    }

    private function calculateCombinedEntropy(string $password): array
    {
        $length = mb_strlen($password, 'UTF-8');

        // Prevents division by zero.
        if ($length === 0) {
            return [
                'strength'          => 'app.password_very_weak',
                'entropy'           => 0.00,
                'estimated_entropy' => 0.00,
                'shannon_entropy'   => 0.00,
            ];
        }

        // --- Estimated Entropy by Character Pool ---
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
            $poolSize += 26; // Assuming symbols are from a set of 26 common symbols as in the password function above
        }

        $estimatedEntropy = ($poolSize > 0)
            ? round($length * log($poolSize, 2), 2)
            : 0.00;

        // --- Shannon Entropy ---
        $charCounts = [];
        for ($i = 0; $i < $length; $i++) {
            $char                                                                 = mb_substr($password, $i, 1, 'UTF-8');
            isset($charCounts[$char]) ? ++$charCounts[$char] : $charCounts[$char] = 1;
        }

        $shannonEntropy = 0.00;
        $invLength      = 1 / $length;
        foreach ($charCounts as $count) {
            $p = $count * $invLength;
            $shannonEntropy -= $p * log($p, 2);
        }
        $shannonEntropy = round($shannonEntropy * $length, 2);

        // --- Final Entropy: Take the maximum ---
        $finalEntropy = max($estimatedEntropy, $shannonEntropy);

        $strength = match (true) {
            $finalEntropy >= 128 => 'app.password_very_strong',
            $finalEntropy >= 80  => 'app.password_strong',
            $finalEntropy >= 60  => 'app.password_moderate',
            $finalEntropy >= 36  => 'app.password_weak',
            default              => 'app.password_very_weak',
        };

        return [
            'strength'          => $strength,
            'entropy'           => $finalEntropy,
            'estimated_entropy' => $estimatedEntropy,
            'shannon_entropy'   => $shannonEntropy,
        ];
    }
}
