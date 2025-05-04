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
abstract class Lds extends \PhpGedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $className = '\\PhpGedcom\\Record\\Indi\\' . ucfirst(strtolower(trim($record[1])));
        $lds = new $className();

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
                case 'STAT':
                    $lds->setStat(trim($record[2]));
                    break;
                case 'DATE':
                    $lds->setDate(trim($record[2]));
                    break;
                case 'PLAC':
                    $lds->setPlac(trim($record[2]));
                    break;
                case 'TEMP':
                    $lds->setTemp(trim($record[2]));
                    break;
                case 'SOUR':
                    $sour = \PhpGedcom\Parser\SourRef::parse($parser);
                    $lds->addSour($sour);
                    break;
                case 'NOTE':
                    $note = \PhpGedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $lds->addNote($note);
                    }
                    break;
                default:
                    $self = get_called_class();
                    $method = 'parse' . $recordType;

                    if (method_exists($self, $method)) {
                        $self::$method($parser, $lds);
                    } else {
                        $parser->logUnhandledRecord($self . ' @ ' . __LINE__);
                    }
            }

            $parser->forward();
        }

        return $lds;
    }
}
