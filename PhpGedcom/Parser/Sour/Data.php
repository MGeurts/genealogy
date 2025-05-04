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

namespace PhpGedcom\Parser\Sour;

/**
 *
 *
 */
class Data extends \PhpGedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $data = new \PhpGedcom\Record\Sour\Data();

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
                case 'EVEN':
                    $data->addEven(\PhpGedcom\Parser\Sour\Data\Even::parse($parser));
                    break;
                case 'DATE':
                    $data->setDate(trim($record[2]));
                    break;
                case 'AGNC':
                    $data->setAgnc(trim($record[2]));
                    break;
                case 'NOTE':
                    $note = \PhpGedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $data->addNote($note);
                    }
                    break;
                case 'TEXT':
                    $data->setText($parser->parseMultiLineRecord());
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $data;
    }
}
