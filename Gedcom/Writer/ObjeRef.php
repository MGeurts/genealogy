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

class ObjeRef
{
    /**
     * @param  \Gedcom\Record\ObjeRef  $note
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\ObjeRef &$obje, $level)
    {
        $output = '';

        // $_note
        $_obje = $obje->getObje();
        if (! empty($_note)) {
            $output .= $level . ' OBJE ' . $_obje . "\n";
        } else {
            $output .= $level . " OBJE \n";
        }

        $level++;
        // _form
        $_form = $obje->getForm();
        if (! empty($_form)) {
            $output .= $level . ' FORM ' . $_form . "\n";
        }

        // _titl
        $_titl = $obje->getTitl();
        if (! empty($_titl)) {
            $output .= $level . ' TITL ' . $_titl . "\n";
        }

        // _file
        $_file = $obje->getFile();
        if (! empty($_file)) {
            $output .= $level . ' FILE ' . $_file . "\n";
        }

        // $_note = array()
        $_note = $obje->getNote();
        if (! empty($_note) && (is_countable($_note) ? count($_note) : 0) > 0) {
            foreach ($_note as $item) {
                $_convert = NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
