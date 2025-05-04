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

namespace Gedcom\Writer\Head;

class Plac
{
    /**
     * @param string $format
     * @param int    $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Head\Plac &$plac, $level)
    {
        $output = $level." PLAC \n";

        // level up
        $level++;
        // FORM
        $form = $plac->getForm();
        if ($form) {
            $output .= $level.' FORM '.$form."\n";
        }

        return $output;
    }
}
