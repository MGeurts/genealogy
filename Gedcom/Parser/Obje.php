<?php

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

class Obje extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $identifier = $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $obje = new \Gedcom\Record\Obje();
        $obje->setId($identifier);

        $parser->getGedcom()->addObje($obje);

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType = strtoupper(trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'FORM':
                    $obje->setForm(trim($record[2]));
                    break;
                case 'TITL':
                    $obje->setTitl(trim($record[2]));
                    break;
                case 'OBJE':
                    $obje->setForm($parser->normalizeIdentifier($record[2]));
                    break;
                case 'RIN':
                    $obje->setRin(trim($record[2]));
                    break;
                case 'REFN':
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $obje->addRefn($refn);
                    break;
                case 'BLOB':
                    $obje->setBlob($parser->parseMultiLineRecord());
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $obje->addNote($note);
                    }
                    break;
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $obje->setChan($chan);
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $obje;
    }
}
