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

class SourRef extends Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];
        if (isset($record[2])) {
            $sour = new \Gedcom\Record\SourRef();
            $sour->setSour($parser->normalizeIdentifier($record[2]));
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

            switch ($recordType) {
                case 'CONT':
                    $sour->setSour($sour->getSour() . "\n");

                    if (isset($record[2])) {
                        $sour->setSour($sour->getSour() . $record[2]);
                    }
                    break;
                case 'CONC':
                    if (isset($record[2])) {
                        $sour->setSour($sour->getSour() . $record[2]);
                    }
                    break;
                case 'TEXT':
                    $sour->setText($parser->parseMultiLineRecord());
                    break;
                case 'NOTE':
                    $note = NoteRef::parse($parser);
                    if ($note) {
                        $sour->addNote($note);
                    }
                    break;
                case 'DATA':
                    $sour->setData(SourRef\Data::parse($parser));
                    break;
                case 'QUAY':
                    $sour->setQuay(mb_trim((string) $record[2]));
                    break;
                case 'PAGE':
                    $sour->setPage(mb_trim((string) $record[2]));
                    break;
                case 'EVEN':
                    $even = SourRef\Even::parse($parser);
                    $sour->setEven($even);
                    break;
                case 'OBJE':
                    $obje = ObjeRef::parse($parser);
                    if ($obje) {
                        $sour->addNote($obje);
                    }
                    break;
                default:
                    $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $sour;
    }
}
