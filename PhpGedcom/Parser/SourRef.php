<?php

declare(strict_types=1);

/**
 * php-gedcom
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

namespace PhpGedcom\Parser;

class SourRef extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];

        $sour = new \PhpGedcom\Record\SourRef();
        $sour->setSour($record[2]);

        $parser->forward();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $recordType   = mb_strtoupper(mb_trim($record[1]));
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
                    $sour->setData(Sour\Data::parse($parser));
                    break;
                case 'QUAY':
                    $sour->setQuay(mb_trim($record[2]));
                    break;
                case 'PAGE':
                    $sour->setPage(mb_trim($record[2]));
                    break;
                case 'EVEN':
                    $even = SourRef\Even::parse($parser);
                    $sour->setEven($even);
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $sour;
    }
}
