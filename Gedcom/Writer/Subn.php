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

class Subn
{
    /**
     * @param \Gedcom\Record\Subn $note
     * @param int                 $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Subn &$subn)
    {
        $level = 0;
        $output = '';
        $_subn = $subn->getSubn();
        if (empty($_subn)) {
            return $output;
        } else {
            $output .= $level.' '.$_subn." SUBN \n";
        }
        // level up
        $level++;

        // SUBM
        $subm = $subn->getSubm();
        if (!empty($subm)) {
            $output .= $level.' SUBM '.$subm."\n";
        }

        // FAMF
        $famf = $subn->getFamf();
        if (!empty($famf)) {
            $output .= $level.' FAMF '.$famf."\n";
        }

        // TEMP
        $temp = $subn->getTemp();
        if (!empty($temp)) {
            $output .= $level.' TEMP '.$temp."\n";
        }

        // ANCE
        $ance = $subn->getAnce();
        if (!empty($ance)) {
            $output .= $level.' ANCE '.$ance."\n";
        }

        // DESC
        $desc = $subn->getDesc();
        if (!empty($desc)) {
            $output .= $level.' DESC '.$desc."\n";
        }
        // ORDI
        $ordi = $subn->getOrdi();
        if (!empty($ordi)) {
            $output .= $level.' ORDI '.$ordi."\n";
        }

        // RIN
        $rin = $subn->getRin();
        if (!empty($rin)) {
            $output .= $level.' RIN '.$rin."\n";
        }

        return $output;
    }
}
