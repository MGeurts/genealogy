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

    public float $passwordEntropy = 0.00;

    public float $estimatedEntropy = 0.00;

    public float $shannonEntropy = 0.00;

    public string $passwordColor = 'red';

    // ----------------------------------------------------------------------------------------------------------
    // In this livewire component, there is no password input. We always generate passwords in a random way.
    // Therefore the Estimated Entropy is always greater than the Shannon Entropy and the more accurate value.
    // We leave the Shannon Entropy calculation here for educational purposes.
    // In case we use a password input in the future, we can use this function to calculate the accurate entropy.
    // ----------------------------------------------------------------------------------------------------------
    public function generate(): void
    {
        $this->validateOnly('length');

        // ----------------------------------------------------------------------------------------
        // You could instead use str::password() if you want to use the built-in Laravel function,
        // but this function allows for more customization, such as specifiing the allowed symbols.
        // ----------------------------------------------------------------------------------------
        $this->generatedPassword = $this->password(
            length: $this->length,
            letters: true,
            numbers: $this->useNumbers,
            symbols: $this->useSymbols,
            spaces: false // Spaces are not included by default, but you can add an option for it if needed.
        );

        $entropyData = $this->evaluatePasswordStrength($this->generatedPassword);

        $this->passwordStrength = $entropyData['strength'];
        $this->passwordEntropy  = $entropyData['entropy'];
        $this->estimatedEntropy = $entropyData['estimated_entropy'];
        $this->shannonEntropy   = $entropyData['shannon_entropy'];

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
    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'length' => __('app.password_length'),
        ];
    }

    // -----------------------------------------------------------------------
    private function password(int $length = 32, bool $letters = true, bool $numbers = true, bool $symbols = true, bool $spaces = false): string
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

        // Collect all options into array and generate remaining characters
        $allChars  = $options->all();
        $remaining = Collection::times($length, fn () => $allChars[random_int(0, count($allChars) - 1)]);

        return $password->merge($remaining)->shuffle()->implode('');
    }

    /**
     * @return array{strength: string, entropy: float, estimated_entropy: float, shannon_entropy: float}
     */
    private function evaluatePasswordStrength(string $password): array
    {
        $estimated = $this->calculateEstimatedEntropy($password);
        $shannon   = $this->calculateShannonEntropy($password);
        $final     = max($estimated, $shannon);

        $strength = match (true) {
            $final >= 128 => 'app.password_very_strong',
            $final >= 80  => 'app.password_strong',
            $final >= 60  => 'app.password_moderate',
            $final >= 36  => 'app.password_weak',
            default       => 'app.password_very_weak',
        };

        return [
            'strength'          => $strength,
            'entropy'           => $final,
            'estimated_entropy' => $estimated,
            'shannon_entropy'   => $shannon,
        ];
    }

    private function calculateEstimatedEntropy(string $password): float
    {
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
            $poolSize += 26; // Adjust if your symbol set is different
        }

        $length = mb_strlen($password, 'UTF-8');

        return ($poolSize > 0)
            ? round($length * log($poolSize, 2), 2)
            : 0.00;
    }

    private function calculateShannonEntropy(string $password): float
    {
        $length = mb_strlen($password, 'UTF-8');
        if ($length === 0) {
            return 0.00;
        }

        $charCounts = [];
        for ($i = 0; $i < $length; $i++) {
            $char              = mb_substr($password, $i, 1, 'UTF-8');
            $charCounts[$char] = ($charCounts[$char] ?? 0) + 1;
        }

        $entropy   = 0.00;
        $invLength = 1 / $length;

        foreach ($charCounts as $count) {
            $p = $count * $invLength;
            $entropy -= $p * log($p, 2);
        }

        return round($entropy * $length, 2);
    }
}
