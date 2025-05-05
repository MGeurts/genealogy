<?php

declare(strict_types=1);

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

namespace Gedcom\Writer\Sour\Data;

class Even
{
    /**
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Sour\Data\Even &$even, $level)
    {
        $output = $level . " EVEN\n";
        $level++;

        // $date;
        $date = $even->getDate();
        if (! empty($date)) {
            $output .= $level . ' DATE ' . $date . "\n";
        }

        // Plac
        $plac = $even->getPlac();
        if (! empty($plac)) {
            $output .= $level . ' PLAC ' . $plac . "\n";
        }

        return $output;
    }
}
