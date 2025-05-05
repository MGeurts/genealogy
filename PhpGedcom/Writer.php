<?php

declare(strict_types=1);

/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom;

use PhpGedcom\Writer\Head;

class Writer
{
    public const GEDCOM55 = 'gedcom5.5';

    protected $_output = null;

    /**
     * @param  Gedcom  $gedcom  The GEDCOM object
     * @param  string  $format  The format to convert the GEDCOM object to
     * @return string The contents of the document in the converted format
     */
    public static function convert(Gedcom $gedcom, $format = self::GEDCOM55)
    {
        $head = $gedcom->getHead();

        $output = Head::convert($head, $format);

        return $output;
    }
}
