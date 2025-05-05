<?php

declare(strict_types=1);

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

namespace Gedcom\Parser\Fam;

class Even extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];

        $even = new \Gedcom\Record\Fam\Even();

        if (isset($record[1]) && mb_strtoupper(mb_trim((string) $record[1])) !== 'EVEN') {
            $even->setType(mb_trim((string) $record[1]));
        }

        $parser->forward();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $recordType   = mb_strtoupper(mb_trim((string) $record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'TYPE':
                    $even->setType(mb_trim((string) $record[2]));
                    break;
                case 'DATE':
                    $dat = \Gedcom\Parser\Date::parse($parser);
                    $even->setDate($dat);
                    // $even->setDate(trim($record[2]));
                    break;
                case 'PLAC':
                    $plac = \Gedcom\Parser\Plac::parse($parser);
                    $even->setPlac($plac);
                    break;
                case 'ADDR':
                    $addr = \Gedcom\Parser\Addr::parse($parser);
                    $even->setAddr($addr);
                    break;
                case 'PHON':
                    $phone = \Gedcom\Parser\Phon::parse($parser);
                    $even->addPhon($phone);
                    break;
                case 'CAUS':
                    $even->setCaus(mb_trim((string) $record[2]));
                    break;
                case 'AGE':
                    $even->setAge(mb_trim((string) $record[2]));
                    break;
                case 'AGNC':
                    $even->setAgnc(mb_trim((string) $record[2]));
                    break;
                case 'HUSB':
                    $husb = Even\Husb::parse($parser);
                    $even->setHusb($husb);
                    break;
                case 'WIFE':
                    $wife = Even\Wife::parse($parser);
                    $even->setWife($wife);
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
                default:
                    $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $even;
    }
}
