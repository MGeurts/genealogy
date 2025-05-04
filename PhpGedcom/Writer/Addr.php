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

namespace PhpGedcom\Writer;

/**
 *
 */
class Addr
{
    /**
     * @param \PhpGedcom\Record\Addr $addr
     * @param string $format
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Addr &$addr, $format = self::GEDCOM55, $level = 1)
    {
        $addrs = explode("\n", $addr->addr);

        $output = "{$level} ADDR " . $addrs[0] . "\n";

        array_shift($addrs);

        foreach ($addrs as $cont) {
            $output .= ($level + 1) . " CONT " . $cont . "\n";
        }

        $output .= ($level + 1) . " ADR1 " . $addr->adr1 . "\n" .
            ($level + 1) . " ADR2 " . $addr->adr2 . "\n" .
            ($level + 1) . " CITY " . $addr->city . "\n" .
            ($level + 1) . " STAE " . $addr->stae . "\n" .
            ($level + 1) . " POST " . $addr->post . "\n" .
            ($level + 1) . " CTRY " . $addr->ctry . "\n";

        return $output;
    }
}
