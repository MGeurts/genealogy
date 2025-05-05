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

namespace Gedcom\Writer\SourRef;

class Even
{
    /**
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\SourRef\Even &$even, $level = 0)
    {
        $output = '';

        // $_date;
        $_even = $even->getEven();
        if (! empty($_even)) {
            $output .= $level . ' EVEN ' . $_even . "\n";
        } else {
            $output = $level . " EVEN\n";
        }
        // level up
        $level++;

        // $_role ROLE
        $_role = $data->getRole();
        if (! empty($_role)) {
            $output .= $level . ' ROLE ' . $_role . "\n";
        }

        return $output;
    }
}
