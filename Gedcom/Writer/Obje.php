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

class Obje
{
    /**
     * @param \Gedcom\Record\Obje $sour
     * @param int                 $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Obje &$obje)
    {
        $level = 0;
        $output = '';
        $id = $obje->getId();
        if ($id) {
            $output .= $level.' '.$id." OBJE\n";
        } else {
            return $output;
        }

        // level up
        $level++;

        // FORM
        $form = $obje->getName();
        if ($form) {
            $output .= $level.' FORM '.$form."\n";
        }

        // TITL
        $titl = $obje->getTitl();
        if ($titl) {
            $output .= $level.' TITL '.$titl."\n";
        }

        // OBJE
        // This is same as FORM

        // RIN
        $rin = $obje->getRin();
        if ($rin) {
            $output .= $level.' RIN '.$rin."\n";
        }

        // REFN
        $refn = $obje->getRefn();
        if (!empty($refn) && (is_countable($refn) ? count($refn) : 0) > 0) {
            foreach ($refn as $item) {
                if ($item) {
                    $_convert = \Gedcom\Writer\Refn::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        // BLOB
        $blob = $obje->getBlob();
        if ($blob) {
            $output .= $level.' BLOB '.$blob."\n";
        }

        // NOTE
        $note = $obje->getNote();
        foreach ($note as $item) {
            if ($item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // CHAN
        $chan = $obje->getChan();
        if ($chan) {
            $_convert = \Gedcom\Writer\Chan::convert($chan, $level);
            $output .= $_convert;
        }

        // FILE
        $file = $obje->getFile();
        if ($file) {
            $output .= $level.' FILE '.$file."\n";
        }

        //
        return $output;
    }
}
