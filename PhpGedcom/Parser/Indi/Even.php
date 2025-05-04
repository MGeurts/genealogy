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

use PhpGedcom\Parser\Chan;

/**
 *
 *
 */
class Even extends \PhpGedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $even = null;

        if (strtoupper(trim($record[1])) != 'EVEN') {
            $className = '\\PhpGedcom\\Record\\Indi\\' . ucfirst(strtolower(trim($record[1])));
            $even = new $className();
        } else {
            $even = new \PhpGedcom\Record\Indi\Even();
        }

        if (isset($record[1]) && strtoupper(trim($record[1])) != 'EVEN') {
            $even->setType(trim($record[1]));
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
                case 'TYPE':
                    $even->setType(trim($record[2]));
                    break;
                case 'DATE':
                    $even->setDate(trim($record[2]));
                    break;
                case 'PLAC':
                    $plac = \PhpGedcom\Parser\Indi\Even\Plac::parse($parser);
                    $even->setPlac($plac);
                    break;
                case 'ADDR':
                    $even->setAddr(\PhpGedcom\Parser\Addr::parse($parser));
                    break;
                case 'PHON':
                    $phone = \PhpGedcom\Parser\Phone::parse($parser);
                    $even->addPhone($phone);
                    break;
                case 'CAUS':
                    $even->setCaus(trim($record[2]));
                    break;
                case 'AGE':
                    $even->setAge(trim($record[2]));
                    break;
                case 'AGNC':
                    $even->setAgnc(trim($record[2]));
                    break;
                case 'SOUR':
                    $sour = \PhpGedcom\Parser\SourRef::parse($parser);
                    $even->addSour($sour);
                    break;
                case 'OBJE':
                    $obje = \PhpGedcom\Parser\ObjeRef::parse($parser);
                    $even->addObje($obje);
                    break;
                case 'NOTE':
                    $note = \PhpGedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $even->addNote($note);
                    }
                    break;
                case 'CHAN':
                    $change = Chan::parse($parser);
                    $even->setChan($change);
                    break;
                default:
                    $self = get_called_class();
                    $method = 'parse' . $recordType;

                    if (method_exists($self, $method)) {
                        $self::$method($parser, $even);
                    } else {
                        $parser->logUnhandledRecord($self . ' @ ' . __LINE__);
                        $parser->skipToNextLevel($currentDepth);
                    }
            }

            $parser->forward();
        }

        return $even;
    }
}
