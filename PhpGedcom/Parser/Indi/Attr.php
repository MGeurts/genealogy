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

namespace PhpGedcom\Parser\Indi;

abstract class Attr extends \PhpGedcom\Parser\Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];

        $className = '\\PhpGedcom\\Record\\Indi\\' . ucfirst(mb_strtolower(mb_trim($record[1])));
        $attr      = new $className();

        $attr->setType(mb_trim($record[1]));

        if (isset($record[2])) {
            $attr->setAttr(mb_trim($record[2]));
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
                case 'TYPE':
                    $attr->setType(mb_trim($record[2]));
                    break;
                case 'DATE':
                    $attr->setDate(mb_trim($record[2]));
                    break;
                case 'PLAC':
                    $plac = Even\Plac::parse($parser);
                    $attr->setPlac($plac);
                    break;
                case 'ADDR':
                    $attr->setAddr(\PhpGedcom\Parser\Addr::parse($parser));
                    break;
                case 'PHON':
                    $phone = \PhpGedcom\Parser\Phon::parse($parser);
                    $attr->addPhon($phone);
                    break;
                case 'CAUS':
                    $attr->setCaus(mb_trim($record[2]));
                    break;
                case 'AGE':
                    $attr->setAge(mb_trim($record[2]));
                    break;
                case 'AGNC':
                    $attr->setAgnc(mb_trim($record[2]));
                    break;
                case 'SOUR':
                    $sour = \PhpGedcom\Parser\SourRef::parse($parser);
                    $attr->addSour($sour);
                    break;
                case 'OBJE':
                    $obje = \PhpGedcom\Parser\ObjeRef::parse($parser);
                    $attr->addObje($obje);
                    break;
                case 'NOTE':
                    $note = \PhpGedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $attr->addNote($note);
                    }
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $attr;
    }
}
