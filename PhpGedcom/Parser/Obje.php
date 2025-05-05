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

class Obje extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record     = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth      = (int) $record[0];

        $obje = new \PhpGedcom\Record\Obje();
        $obje->setId($identifier);

        $parser->getGedcom()->addObje($obje);

        $parser->forward();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType   = mb_strtoupper(mb_trim($record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'FORM':
                    $obje->setForm(mb_trim($record[2]));
                    break;
                case 'TITL':
                    $obje->setTitl(mb_trim($record[2]));
                    break;
                case 'OBJE':
                    $obje->setForm($parser->normalizeIdentifier($record[2]));
                    break;
                case 'RIN':
                    $obje->setRin(mb_trim($record[2]));
                    break;
                case 'REFN':
                    $refn = Refn::parse($parser);
                    $obje->addRefn($refn);
                    break;
                case 'BLOB':
                    $obje->setBlob($parser->parseMultiLineRecord());
                    break;
                case 'NOTE':
                    $note = NoteRef::parse($parser);
                    if ($note) {
                        $obje->addNote($note);
                    }
                    break;
                case 'CHAN':
                    $chan = Chan::parse($parser);
                    $obje->setChan($chan);
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $obje;
    }
}
