<?php

declare(strict_types=1);

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom\Parser;

class Date extends Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();

        if (isset($record[1])) {
            $dat = new \Gedcom\Record\Date();
            if (! empty($record[2])) {
                $dat->setDate($record[2]);
            }
        } else {
            return null;
        }

        if ($dat->getYear() && $dat->getMonth() && $dat->getDay()) {
            return $dat->getYear() . '-' . mb_substr("0{$dat->getMonth()}", -2) . '-' . mb_substr("0{$dat->getDay()}", -2);
        }

        if ($dat->getYear() && $dat->getMonth()) {
            return $dat->getYear() . '-' . mb_substr("0{$dat->getMonth()}", -2);
        }

        return $dat->getYear();
    }
}
