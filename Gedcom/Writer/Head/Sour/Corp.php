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

namespace Gedcom\Writer\Head\Sour;

class Corp
{
    /**
     * @param string $format
     * @param int    $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Head\Sour\Corp &$corp, $level)
    {
        $output = '';
        $_corp = $corp->getCorp();
        if ($_corp) {
            $output .= $level.' CORP '.$_corp."\n";
        } else {
            return $output;
        }

        // level up
        $level++;

        // ADDR
        $addr = $corp->getAddr();
        if ($addr) {
            $_convert = \Gedcom\Writer\Addr::convert($addr, $level);
            $output .= $_convert;
        }

        // phon
        $phon = $corp->getPhon();
        foreach ($phon as $item) {
            if ($item) {
                $_convert = \Gedcom\Writer\Phon::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
