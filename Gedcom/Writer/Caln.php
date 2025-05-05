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

namespace Gedcom\Writer;

class Caln
{
    /**
     * @param  \Gedcom\Record\Caln  $note
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Caln &$caln, $level)
    {
        $output = '';
        $_caln  = $caln->getCaln();
        if (empty($_caln)) {
            return $output;
        }
        $output .= $level . ' CALN ' . $_caln . "\n";

        // level up
        $level++;

        // medi
        $medi = $caln->getMedi();
        if (! empty($medi)) {
            $output .= $level . ' MEDI ' . $medi . "\n";
        }

        return $output;
    }
}
