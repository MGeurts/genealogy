<?php

declare(strict_types=1);

namespace App\Php\Gedcom;

final class Export
{
    public function __construct(public string $filename, public string $format = 'gedcom', public string $encoding = 'utf8', public string $line_endings = 'windows') {}

    public function Export(): void
    {
        //
    }
}
