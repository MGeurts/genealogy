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

class Romn extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];
        if (isset($record[2])) {
            $romn = new \Gedcom\Record\Indi\Name\Romn();
            $romn->setRomn(mb_trim((string) $record[2]));
        } else {
            $parser->skipToNextLevel($depth);

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
                'TYPE'  => $romn->setRomn(mb_trim((string) $record[2])),
                'NPFX'  => $romn->setNpfx(mb_trim((string) $record[2])),
                'GIVN'  => $romn->setGivn(mb_trim((string) $record[2])),
                'NICK'  => $romn->setNick(mb_trim((string) $record[2])),
                'SPFX'  => $romn->setSpfx(mb_trim((string) $record[2])),
                'SURN'  => $romn->setSurn(mb_trim((string) $record[2])),
                'NSFX'  => $romn->setNsfx(mb_trim((string) $record[2])),
                default => $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__),
            };

            $parser->forward();
        }

        return $romn;
    }
}
