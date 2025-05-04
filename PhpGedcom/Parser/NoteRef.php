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
 */
class NoteRef extends \PhpGedcom\Parser\Component
{
    /**
     *
     */
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $note = new \PhpGedcom\Record\NoteRef();

        if (count($record) < 3) {
            $parser->logSkippedRecord('Missing note information; ' . get_class(), ' @ ' . __LINE__);
            $parser->skipToNextLevel($depth);
            return null;
        }

        if (preg_match('/^@(.*)@$/', trim($record[2]))) {
            $note->setIsReference(true);
            $note->setNote($parser->normalizeIdentifier($record[2]));
        } else {
            $before = $parser->getCurrentLine();
            $note->setIsReference(false);
            $note->setNote($parser->parseMultiLineRecord());
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
                case 'SOUR':
                    $sour = \PhpGedcom\Parser\SourRef::parse($parser);
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
