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

class NoteRef extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $note = new \Gedcom\Record\NoteRef();

        if ((is_countable($record) ? count($record) : 0) < 3) {
            $parser->logSkippedRecord('Missing note information; '.self::class);
            $parser->skipToNextLevel($depth);

            return null;
        }

        if (preg_match('/^@(.*)@$/', trim((string) $record[2]))) {
            $note->setIsReference(true);
            $note->setNote($parser->normalizeIdentifier($record[2]));
        } else {
            $parser->getCurrentLine();
            $note->setIsReference(false);
            $note->setNote($parser->parseMultiLineRecord());
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
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $note->addSour($sour);
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $note;
    }
}
