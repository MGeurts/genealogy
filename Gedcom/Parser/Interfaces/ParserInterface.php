<?php

declare(strict_types=1);

namespace Gedcom\Parser\Interfaces;

use Gedcom\Gedcom;

interface ParserInterface
{
    /**
     * Initiates the parsing of a GEDCOM file.
     *
     * @param  string  $fileName  The path to the GEDCOM file to be parsed.
     */
    public function parse(string $fileName): ?Gedcom;

    /**
     * Advances the parser to the next line in the GEDCOM file.
     */
    public function forward(): void;

    /**
     * Moves the parser back to the previously read line in the GEDCOM file.
     */
    public function back(): void;

    /**
     * Checks if the end of the GEDCOM file has been reached.
     *
     * @return bool True if the end of the file has been reached, false otherwise.
     */
    public function eof(): bool;
}
