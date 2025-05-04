<?php

/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2020, Kristopher Wilson
 * @package         php-gedcom
 * @license         MIT
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Writer\Head;

/**
 *
 */
class Sour
{
    /**
     * @param \PhpGedcom\Record\Head\Sour $sour
     * @param string $format
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Head\Sour &$sour, $format = self::GEDCOM55, $level = 0)
    {
        $output = "1 SOUR " . $sour->sour . "\n" .
            "2 VERS " . $sour->vers . "\n" .
            \PhpGedcom\Writer\Head\Sour\Corp::convert($sour->corp, $format, 2) .
            // TODO DATA;
            "";

        /*
              +2 DATA <NAME_OF_SOURCE_DATA>  {0:1}
                +3 DATE <PUBLICATION_DATE>  {0:1}
                +3 COPR <COPYRIGHT_SOURCE_DATA>  {0:1}
        */

        return $output;
    }
}
