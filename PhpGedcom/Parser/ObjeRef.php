<?php

/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @package         php-gedcom
 * @license         MIT
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Parser;

/**
 *
 *
 */
class ObjeRef extends \PhpGedcom\Parser\Component
{
    /**
     *
     */
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $obje = new \PhpGedcom\Record\ObjeRef();

        if (isset($record[2])) {
            $obje->setIsReference(true);
            $obje->setObje($parser->normalizeIdentifier($record[2]));
        } else {
            $obje->setIsReference(false);
        }

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim($record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'TITL':
                    $obje->setTitl(trim($record[2]));
                    break;
                case 'FILE':
                    $obje->setFile(trim($record[2]));
                    break;
                case 'FORM':
                    $obje->setForm(trim($record[2]));
                    break;
                case 'NOTE':
                    $note = \PhpGedcom\Parser\NoteRef::parse($parser);
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
