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

class Subn extends \Gedcom\Parser\Component
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

        $subn = new \Gedcom\Record\Subn();
        $subn->setSubn($identifier);

        $parser->getGedcom()->setSubn($subn);

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
                case 'SUBM':
                    $subn->setSubm($parser->normalizeIdentifier($record[2]));
                    break;
                case 'FAMF':
                    $subn->setFamf(trim((string) $record[2]));
                    break;
                case 'TEMP':
                    $subn->setTemp(trim((string) $record[2]));
                    break;
                case 'ANCE':
                    $subn->setAnce(trim((string) $record[2]));
                    break;
                case 'DESC':
                    $subn->setDesc(trim((string) $record[2]));
                    break;
                case 'ORDI':
                    $subn->setOrdi(trim((string) $record[2]));
                    break;
                case 'RIN':
                    $subn->setRin(trim((string) $record[2]));
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $subn->addNote($note);
                    }
                    break;
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $subn->setChan($chan);
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $subn;
    }
}
