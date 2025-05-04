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

namespace Gedcom\Writer;

class Refn
{
    /**
     * @param \Gedcom\Record\Refn $note
     * @param int                 $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Refn &$refn, $level)
    {
        $output = '';
        $_refn = $refn->getRefn();
        if (empty($_refn)) {
            return $output;
        } else {
            $output .= $level.' REFN '.$_refn."\n";
        }
        // level up
        $level++;
        // DATE
        $type = $refn->getType();
        if (!empty($type)) {
            $output .= $level.' TYPE '.$type."\n";
        }

        return $output;
    }
}
