<?php

declare(strict_types=1);

namespace App\Php\Gedcom;

final class Export
{
    public string $filename;

    public string $format;

    public string $encoding;

    public string $line_endings;

    public function __construct(string $filename, string $format = 'gedcom', string $encoding = 'utf8', string $line_endings = 'windows')
    {
        $this->filename     = $filename;
        $this->format       = $format;
        $this->encoding     = $encoding;
        $this->line_endings = $line_endings;
    }

    public function Export(): void
    {
        //
    }
}
