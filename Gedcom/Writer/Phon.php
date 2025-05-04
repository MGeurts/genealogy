<?php

/**
 * php-gedcom.
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

namespace Gedcom\Writer;

class Phon
{
    /**
     * @param string $phon
     * @param string $format
     * @param int    $level
     *
     * @return string
     */
    public static function convert($phon, $level = 1)
    {
        return "{$level} PHON ".$phon."\n";
    }
}
