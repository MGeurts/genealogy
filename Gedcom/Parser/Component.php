<?php

declare(strict_types=1);

namespace Gedcom\Parser;

use Gedcom\Parser as GedcomParser;

abstract class Component
{
    abstract public static function parse(GedcomParser $parser): mixed;
}