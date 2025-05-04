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

class Sour
{
    /**
     * @param string $format
     * @param int    $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Head\Sour &$sour, $level)
    {
        $output = '';
        $_sour = $sour->getSour();
        if ($_sour) {
            $output .= $level.' SOUR '.$_sour."\n";
        } else {
            return $output;
        }

        // level up
        $level++;

        // VERS
        $vers = $sour->getVersion();
        if ($vers) {
            $output .= $level.' VERS '.$vers."\n";
        }

        // NAME
        $name = $sour->getName();
        if ($name) {
            $output .= $level.' NAME '.$name."\n";
        }

        // CORP
        $corp = $sour->getCorp();
        if ($corp) {
            $_convert = \Gedcom\Writer\Head\Sour\Corp::convert($corp, $level);
            $output .= $_convert;
        }

        // DATA
        $data = $sour->getData();
        if ($data) {
            $_convert = \Gedcom\Writer\Head\Sour\Data::convert($data, $level);
            $output .= $_convert;
        }

        return $output;
    }
}
