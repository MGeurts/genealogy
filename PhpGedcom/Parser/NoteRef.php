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

class NoteRef extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];

        $note = new \PhpGedcom\Record\NoteRef();

        if (count($record) < 3) {
            $parser->logSkippedRecord('Missing note information; ' . get_class(), ' @ ' . __LINE__);
            $parser->skipToNextLevel($depth);

            return null;
        }

        if (preg_match('/^@(.*)@$/', mb_trim($record[2]))) {
            $note->setIsReference(true);
            $note->setNote($parser->normalizeIdentifier($record[2]));
        } else {
            $before = $parser->getCurrentLine();
            $note->setIsReference(false);
            $note->setNote($parser->parseMultiLineRecord());
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
                case 'SOUR':
                    $sour = SourRef::parse($parser);
                    $note->addSour($sour);
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $note;
    }
}
