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

namespace Gedcom\Writer\Indi;

class Even
{
    /**
     * @param int $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Indi\Even &$even, $level = 0)
    {
        $output = '';

        // $_attr;
        $attr = $even->getAttr();
        if (!empty($attr)) {
            $output .= $level.' EVEN '.$attr."\n";
        } else {
            $output = $level." EVEN\n";
        }
        $level++;

        // $type;
        $type = $even->getType();
        if (!empty($type)) {
            $output .= $level.' TYPE '.$type."\n";
        }

        // $date;
        $date = $even->getDate();
        if (!empty($date)) {
            $output .= $level.' DATE '.$date."\n";
        }

        // Plac
        $plac = $even->getPlac();
        if (!empty($plac)) {
            $_convert = \Gedcom\Writer\Indi\Even\Plac::convert($plac, $level);
            $output .= $_convert;
        }

        // $caus;
        $caus = $even->getCaus();
        if (!empty($caus)) {
            $output .= $level.' CAUS '.$caus."\n";
        }

        // $age;
        $age = $even->getAge();
        if (!empty($age)) {
            $output .= $level.' AGE '.$age."\n";
        }

        // $addr
        $addr = $even->getAddr();
        if (!empty($addr)) {
            $_convert = \Gedcom\Writer\Addr::convert($addr, $level);
            $output .= $_convert;
        }

        // $phon = array()
        $phon = $even->getPhon();
        if (!empty($phon) && $phon !== []) {
            foreach ($phon as $item) {
                $_convert = \Gedcom\Writer\Phon::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $agnc
        $agnc = $even->getAgnc();
        if (!empty($agnc)) {
            $output .= $level.' AGNC '.$agnc."\n";
        }

        // $ref = array();
        // This is not in parser

        // $obje = array();
        $obje = $even->getObje();
        if (!empty($obje) && $obje !== []) {
            foreach ($obje as $item) {
                $_convert = \Gedcom\Writer\ObjeRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $sour = array();
        $sour = $even->getSour();
        if (!empty($sour) && $sour !== []) {
            foreach ($sour as $item) {
                $_convert = \Gedcom\Writer\SourRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $note = array();
        $note = $even->getSour();
        if (!empty($note) && $note !== []) {
            foreach ($note as $item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // Record\Chan
        $chan = $even->getChan();
        if (!empty($chan)) {
            $_convert = \Gedcom\Writer\Chan::convert($item, $level);
            $output .= $_convert;
        }

        return $output;
    }
}
