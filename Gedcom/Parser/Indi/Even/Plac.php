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

namespace Gedcom\Parser\Indi\Even;

class Plac extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $plac = new \Gedcom\Record\Indi\Even\Plac();

        if (isset($record[2])) {
            $plac->setPlac(trim((string) $record[2]));
        }

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim((string) $record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'FORM':
                    $plac->setForm(trim((string) $record[2]));
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $plac->addNote($note);
                    }
                    break;
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $plac->addSour($sour);
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $plac;
    }
}
