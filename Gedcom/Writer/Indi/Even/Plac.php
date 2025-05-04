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

namespace Gedcom\Writer\Indi\Even;

class Plac
{
    /**
     * @param int $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Indi\Even\Plac &$plac, $level = 0)
    {
        $output = '';

        // $plac
        $_plac = $plac->getPlac();
        if (!empty($_plac)) {
            $output .= $level.' PLAC '.$_plac."\n";
        } else {
            $output .= $level." PLAC\n";
        }

        // level up
        $level++;

        // $form
        $form = $plac->getForm();
        if (!empty($form)) {
            $output .= $level.' FORM '.$form."\n";
        }

        // $note -array
        $note = $plac->getNote();
        foreach ($note as $item) {
            $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
            $output .= $_convert;
        }
        // $sour -array
        $sour = $plac->getSour();
        foreach ($sour as $item) {
            $_convert = \Gedcom\Writer\SourRef::convert($item, $level);
            $output .= $_convert;
        }

        return $output;
    }
}
