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

namespace Gedcom\Writer\Head;

class Gedc
{
    /**
     * @param  string  $format
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Head\Gedc &$gedc, $level)
    {
        $output = $level . " GEDC \n";

        // level up
        $level++;
        // VERS
        $vers = $gedc->getVersion();
        if ($vers) {
            $output .= $level . ' VERS ' . $vers . "\n";
        }

        // FORM
        $form = $gedc->getForm();
        if ($form) {
            $output .= $level . ' FORM ' . $form . "\n";
        }

        return $output;
    }
}
