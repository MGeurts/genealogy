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

namespace Gedcom\Writer\Fam;

class Even
{
    /**
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Fam\Even &$even, $eventType, $level)
    {
        $output = '';

        // $type;
        if (! empty($eventType)) {
            $output .= $level . ' ' . $eventType . "\n";
        } else {
            return $output;
        }
        $level++;

        // $type;
        $type = $even->getType();
        if (! empty($type) && $type !== $eventType) {
            $output .= $level . ' TYPE ' . $type . "\n";
        }

        // $date;
        $date = $even->getDate();
        if (! empty($date)) {
            $output .= $level . ' DATE ' . $date . "\n";
        }

        // Plac
        $plac = $even->getPlac();
        if (! empty($plac)) {
            $_convert = \Gedcom\Writer\Indi\Even\Plac::convert($plac, $level);
            $output .= $_convert;
        }

        // $caus;
        $caus = $even->getCaus();
        if (! empty($caus)) {
            $output .= $level . ' CAUS ' . $caus . "\n";
        }

        // $age;
        $age = $even->getAge();
        if (! empty($age)) {
            $output .= $level . ' AGE ' . $age . "\n";
        }

        // $addr
        $addr = $even->getAddr();
        if (! empty($addr)) {
            $_convert = \Gedcom\Writer\Addr::convert($addr, $level);
            $output .= $_convert;
        }

        // $phon = array()
        $phon = $even->getPhon();
        if (! empty($phon) && (is_countable($phon) ? count($phon) : 0) > 0) {
            foreach ($phon as $item) {
                $_convert = \Gedcom\Writer\Phon::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $agnc
        $agnc = $even->getAgnc();
        if (! empty($agnc)) {
            $output .= $level . ' AGNC ' . $agnc . "\n";
        }

        // HUSB
        $husb = $even->getHusb();
        if (! empty($husb)) {
            $_convert = Even\Husb::convert($husb, $level);
            $output .= $_convert;
        }

        // WIFE
        $wife = $even->getWife();
        if (! empty($wife)) {
            $_convert = Even\Wife::convert($wife, $level);
            $output .= $_convert;
        }

        // $ref = array();
        // This is not in parser

        // $obje = array();
        $obje = $even->getObje();
        if (! empty($obje) && $obje !== []) {
            foreach ($obje as $item) {
                $_convert = \Gedcom\Writer\ObjeRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $sour = array();
        $sour = $even->getSour();
        if (! empty($sour) && $sour !== []) {
            foreach ($sour as $item) {
                $_convert = \Gedcom\Writer\SourRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $note = array();
        $note = $even->getNote();
        if (! empty($note) && $note !== []) {
            foreach ($note as $item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
