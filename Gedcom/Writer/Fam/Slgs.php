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

namespace Gedcom\Writer\Fam;

class Slgs
{
    /**
     * @param int $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Fam\Slgs &$slgs, $level)
    {
        $output = '';
        $output .= $level." SLGS \n";

        // Level up
        $level++;

        // $STAT;
        $stat = $slgs->getStat();
        if (!empty($stat)) {
            $output .= $level.' STAT '.$stat."\n";
        }

        // $date;
        $date = $slgs->getDate();
        if (!empty($date)) {
            $output .= $level.' DATE '.$date."\n";
        }

        // PLAC
        $plac = $slgs->getPlac();
        if (!empty($plac)) {
            $output .= $level.' PLAC '.$plac."\n";
        }

        // $TEMP;
        $temp = $slgs->getTemp();
        if (!empty($temp)) {
            $output .= $level.' TEMP '.$temp."\n";
        }

        // $sour = array();
        $sour = $slgs->getSour();
        if (!empty($sour) && (is_countable($sour) ? count($sour) : 0) > 0) {
            foreach ($sour as $item) {
                $_convert = \Gedcom\Writer\SourRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $note = array();
        $note = $slgs->getNote();
        if (!empty($note) && (is_countable($note) ? count($note) : 0) > 0) {
            foreach ($note as $item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
