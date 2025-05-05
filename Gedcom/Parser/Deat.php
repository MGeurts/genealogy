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

class Deat extends Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];

        $parser->forward();

        $deat = new \Gedcom\Record\Deat();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $recordType   = mb_trim((string) $record[1]);
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            match ($recordType) {
                'DATE'  => $deat->setDate(mb_trim((string) $record[2])),
                '_DATI' => $deat->setDati(mb_trim((string) $record[2])),
                'PLAC'  => $deat->setPlac(mb_trim((string) $record[2])),
                'CAUS'  => $deat->setCaus(mb_trim((string) $record[2])),
                default => $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__),
            };

            $parser->forward();
        }

        return $deat;
    }
}
