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

namespace Gedcom\Parser\Indi\Name;

class Fone extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];
        if (isset($record[2])) {
            $fone = new \Gedcom\Record\Indi\Name\Fone();
            $fone->setFone(mb_trim((string) $record[2]));
        } else {
            return null;
        }

        $parser->forward();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $recordType   = mb_strtoupper(mb_trim((string) $record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            if (! isset($record[2])) {
                $record[2] = '';
            }

            match ($recordType) {
                'TYPE'  => $fone->setType(mb_trim((string) $record[2])),
                'NPFX'  => $fone->setNpfx(mb_trim((string) $record[2])),
                'GIVN'  => $fone->setGivn(mb_trim((string) $record[2])),
                'NICK'  => $fone->setNick(mb_trim((string) $record[2])),
                'SPFX'  => $fone->setSpfx(mb_trim((string) $record[2])),
                'SURN'  => $fone->setSurn(mb_trim((string) $record[2])),
                'NSFX'  => $fone->setNsfx(mb_trim((string) $record[2])),
                default => $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__),
            };

            $parser->forward();
        }

        return $fone;
    }
}
