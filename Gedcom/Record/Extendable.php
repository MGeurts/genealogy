<?php

declare(strict_types=1);

namespace Gedcom\Record;

interface Extendable
{
    public function addExtensionTag(string $tag, string $value);

    public function getExtensionTag(string $tag): string;
}
