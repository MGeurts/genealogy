<?php

declare(strict_types=1);

namespace Gedcom;

use Gedcom\Parser\Interfaces\ParserInterface;
use InvalidArgumentException;

class Parser implements ParserInterface
{
    private Gedcom $gedcom;

    private array $lineBuffer = [];

    private int $currentLine = 0;

    public function parse(string $fileName): ?Gedcom
    {
        if (! file_exists($fileName)) {
            throw new InvalidArgumentException("File not found: $fileName");
        }

        $this->lineBuffer  = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->currentLine = 0;
        $this->gedcom      = new Gedcom();

        // Parsing implementation
        return $this->getGedcom();
    }

    public function forward(): void
    {
        $this->currentLine++;
    }

    public function back(): void
    {
        $this->currentLine--;
    }

    public function eof(): bool
    {
        return $this->currentLine >= count($this->lineBuffer);
    }
}
