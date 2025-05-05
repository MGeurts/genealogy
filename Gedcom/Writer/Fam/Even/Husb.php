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

namespace Gedcom\Writer\Fam\Even;

class Husb
{
    /**
     * @param  \Gedcom\Record\Fam\Even\Husb  $attr
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Fam\Even\Husb &$husb, $level = 0)
    {
        $output = '';

        $output .= $level . " HUSB \n";
        // level up
        $level++;

        // AGE
        $age = $husb->getAge();
        if (! empty($age)) {
            $output .= $level . ' AGE ' . $age . "\n";
        }

        return $output;
    }
}
