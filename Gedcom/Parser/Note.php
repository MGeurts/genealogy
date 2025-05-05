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

class Note extends Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord(4);
        $depth  = (int) $record[0];
        if (isset($record[1])) {
            $identifier = $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $note = new \Gedcom\Record\Note();
        $note->setId($identifier);

        if (isset($record[3])) {
            $note->setNote($record[3]);
        }

        $parser->forward();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType   = mb_strtoupper(mb_trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'CONT':
                    $note->setNote($note->getNote() . "\n");
                    if (isset($record[2])) {
                        $note->setNote($note->getNote() . $record[2]);
                    }
                    break;
                case 'CONC':
                    if (isset($record[2])) {
                        $note->setNote($note->getNote() . $record[2]);
                    }
                    break;
                case 'REFN':
                    $refn = Refn::parse($parser);
                    $note->addRefn($refn);
                    break;
                case 'RIN':
                    $note->setRin(mb_trim((string) $record[2]));
                    break;
                case 'SOUR':
                    $sour = SourRef::parse($parser);
                    $note->addSour($sour);
                    break;
                case 'CHAN':
                    $chan = Chan::parse($parser);
                    $note->setChan($chan);
                    break;
                default:
                    $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $parser->forward();
        }
        $parser->getGedcom()->addNote($note);

        return $note;
    }
}
