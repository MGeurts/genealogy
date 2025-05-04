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
 *
 */
class Subm extends \PhpGedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int) $record[0];

        $subm = new \PhpGedcom\Record\Subm();
        $subm->setSubm($identifier);

        $parser->getGedcom()->addSubm($subm);

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType = strtoupper(trim($record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'NAME':
                    $subm->setName(isset($record[2]) ? trim($record[2]) : '');
                    break;
                case 'ADDR':
                    $addr = \PhpGedcom\Parser\Addr::parse($parser);
                    $subm->setAddr($addr);
                    break;
                case 'PHON':
                    $phone = \PhpGedcom\Parser\Phon::parse($parser);
                    $subm->addPhon($phone);
                    break;
                case 'NOTE':
                    $note = \PhpGedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $subm->addNote($note);
                    }
                    break;
                case 'OBJE':
                    $obje = \PhpGedcom\Parser\ObjeRef::parse($parser);
                    $subm->addObje($obje);
                    break;
                case 'CHAN':
                    $chan = \PhpGedcom\Parser\Chan::parse($parser);
                    $subm->setChan($chan);
                    break;
                case 'RIN':
                    $subm->setRin(isset($record[2]) ? trim($record[2]) : '');
                    break;
                case 'RFN':
                    $subm->setRfn(isset($record[2]) ? trim($record[2]) : '');
                    break;
                case 'LANG':
                    $subm->addLang(isset($record[2]) ? trim($record[2]) : '');
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $subm;
    }
}
