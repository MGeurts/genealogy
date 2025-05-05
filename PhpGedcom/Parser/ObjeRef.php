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

class ObjeRef extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];

        $obje = new \PhpGedcom\Record\ObjeRef();

        if (isset($record[2])) {
            $obje->setIsReference(true);
            $obje->setObje($parser->normalizeIdentifier($record[2]));
        } else {
            $obje->setIsReference(false);
        }

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
                case 'TITL':
                    $obje->setTitl(mb_trim($record[2]));
                    break;
                case 'FILE':
                    $obje->setFile(mb_trim($record[2]));
                    break;
                case 'FORM':
                    $obje->setForm(mb_trim($record[2]));
                    break;
                case 'NOTE':
                    $note = NoteRef::parse($parser);
                    if ($note) {
                        $obje->addNote($note);
                    }
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $obje;
    }
}
