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

namespace Gedcom\Writer\Head;

class Char
{
    /**
     * @param  string  $format
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Head\Char &$char, $level)
    {
        $output = '';
        // char
        $_char = $char->getChar();
        if ($_char) {
            $output .= $level . ' CHAR ' . $_char . "\n";
        } else {
            return $output;
        }

        // level up
        $level++;
        // VERS
        $vers = $char->getVersion();
        if ($vers) {
            $output .= $level . ' VERS ' . $vers . "\n";
        }

        return $output;
    }
}
