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

class Asso
{
    /**
     * @param \Gedcom\Record\Indi\Asso $attr
     * @param int                      $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Indi\Asso &$asso, $level = 0)
    {
        $output = '';
        // _indi
        $_indi = $asso->getIndi();
        if (empty($_indi)) {
            return $output;
        }
        $output .= $level.' ASSO '.$_indi."\n";
        // level up
        $level++;

        // RELA
        $rela = $asso->getRela();
        if (!empty($rela)) {
            $output .= $level.' RELA '.$rela."\n";
        }
        // sour
        $sour = $asso->getSour();
        if (!empty($sour) && (is_countable($sour) ? count($sour) : 0) > 0) {
            foreach ($sour as $item) {
                $_convert = \Gedcom\Writer\SourRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // note
        $note = $asso->getSour();
        if (!empty($note) && (is_countable($note) ? count($note) : 0) > 0) {
            foreach ($note as $item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
