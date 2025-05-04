<?php

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Xiang Ming <wenqiangliu344@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Xiang Ming
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom\Writer\Head;

class Date
{
    /**
     * @param string $format
     * @param int    $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Head\Date &$date, $level)
    {
        $output = '';
        $_date = $date->getDate();
        if ($_date) {
            $output .= $level.' DATE '.$_date."\n";
        } else {
            return $output;
        }

        // level up
        $level++;
        // Time
        $time = $date->getTime();
        if ($time) {
            $output .= $level.' TIME '.$time."\n";
        }

        return $output;
    }
}
