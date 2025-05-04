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

namespace PhpGedcom\Parser\Indi;

/**
 *
 *
 */
class Name extends \PhpGedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $name = new \PhpGedcom\Record\Indi\Name();
        $name->setName(trim($record[2]));

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
                case 'NPFX':
                    $name->setNpfx(trim($record[2]));
                    break;
                case 'GIVN':
                    $name->setGivn(trim($record[2]));
                    break;
                case 'NICK':
                    $name->setNick(trim($record[2]));
                    break;
                case 'SPFX':
                    $name->setSpfx(trim($record[2]));
                    break;
                case 'SURN':
                    $name->setSurn(trim($record[2]));
                    break;
                case 'NSFX':
                    $name->setNsfx(trim($record[2]));
                    break;
                case 'SOUR':
                    $sour = \PhpGedcom\Parser\SourRef::parse($parser);
                    $name->addSour($sour);
                    break;
                case 'NOTE':
                    $note = \PhpGedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $name->addNote($note);
                    }
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $name;
    }
}
