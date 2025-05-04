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

class Even extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (empty($record[1])) {
            $parser->skipToNextLevel($depth);

            return null;
        }

        if (strtoupper(trim((string) $record[1])) != 'EVEN') {
            $className = '\Gedcom\Record\Indi\\' . ucfirst(strtolower(trim((string) $record[1])));
            $even = new $className();
        } else {
            $even = new \Gedcom\Record\Indi\Even();
        }

        if (isset($record[1]) && strtoupper(trim((string) $record[1])) != 'EVEN') {
            $even->setType(trim((string) $record[1]));
        }

        // ensures we capture any data following the EVEN type
        if (isset($record[2]) && !empty($record[2])) {
            $even->setAttr(trim((string) $record[2]));
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
                case 'TYPE':
                    $even->setType(trim((string) $record[2]));
                    break;
                case 'DATE':
                    $dat = \Gedcom\Parser\Date::parse($parser);
                    $even->setDate($dat);
                    //$even->setDate(trim($record[2]))
                    break;
                case 'PLAC':
                    $plac = \Gedcom\Parser\Indi\Even\Plac::parse($parser);
                    $even->setPlac($plac);
                    break;
                case 'ADDR':
                    $even->setAddr(\Gedcom\Parser\Addr::parse($parser));
                    break;
                case 'PHON':
                    $phone = \Gedcom\Parser\Phon::parse($parser);
                    $even->addPhone($phone);
                    break;
                case 'CAUS':
                    $even->setCaus(trim((string) $record[2]));
                    break;
                case 'AGE':
                    $even->setAge($record);
                    break;
                case 'AGNC':
                    $even->setAgnc(trim((string) $record[2]));
                    break;
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $even->addSour($sour);
                    break;
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    $even->addObje($obje);
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $even->addNote($note);
                    }
                    break;
                case 'CHAN':
                    $change = \Gedcom\Parser\Chan::parse($parser);
                    $even->setChan($change);
                    break;
                default:
                    $self = static::class;
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
