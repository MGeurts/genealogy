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

namespace Gedcom\Parser\Indi;

abstract class Lds extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $className = '\\Gedcom\\Record\\Indi\\'.ucfirst(strtolower(trim((string) $record[1])));
            $lds = new $className();
        } else {
            $parser->skipToNextLevel($depth);

            return null;
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
                case 'STAT':
                    $lds->setStat(trim((string) $record[2]));
                    break;
                case 'DATE':
                    $lds->setDate(trim((string) $record[2]));
                    break;
                case 'PLAC':
                    $lds->setPlac(trim((string) $record[2]));
                    break;
                case 'TEMP':
                    $lds->setTemp(trim((string) $record[2]));
                    break;
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $lds->addSour($sour);
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $lds->addNote($note);
                    }
                    break;
                default:
                    $self = static::class;
                    $method = 'parse'.$recordType;

                    if (method_exists($self, $method)) {
                        $self::$method($parser, $lds);
                    } else {
                        $parser->logUnhandledRecord($self.' @ '.__LINE__);
                    }
            }

            $parser->forward();
        }

        return $lds;
    }
}
