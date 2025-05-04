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

class Name extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[2])) {
            $name = new \Gedcom\Record\Indi\Name();
            $name->setName(trim((string) $record[2]));
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

            if (!isset($record[2])) {
                $record[2] = '';
            }

            switch ($recordType) {
                case 'TYPE':
                    $name->setType(trim((string) $record[2]));
                    break;
                case 'NPFX':
                    $name->setNpfx(trim((string) $record[2]));
                    break;
                case 'GIVN':
                    $name->setGivn(trim((string) $record[2]));
                    break;
                case 'NICK':
                    $name->setNick(trim((string) $record[2]));
                    break;
                case 'SPFX':
                    $name->setSpfx(trim((string) $record[2]));
                    break;
                case 'SURN':
                    $name->setSurn(trim((string) $record[2]));
                    break;
                case 'NSFX':
                    $name->setNsfx(trim((string) $record[2]));
                    break;
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $name->addSour($sour);
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $name->addNote($note);
                    }
                    break;
                case 'FONE':
                    $name->setFone(\Parser\Indi\Name\Fone::parse($parser));
                    break;
                case 'ROMN':
                    $name->setRomn(\Parser\Indi\Name\Romn::parse($parser));
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $name;
    }
}
