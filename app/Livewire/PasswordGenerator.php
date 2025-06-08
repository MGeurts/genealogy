<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Str;
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

    public function generate(): void
    {
        $this->validateOnly('length');

        $this->generatedPassword = Str::password(
            length: $this->length,
            letters: true,
            numbers: $this->useNumbers,
            symbols: $this->useSymbols
        );
    }

    public function render(): View
    {
        return view('livewire.password-generator');
    }
}
