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

namespace Gedcom\Parser;

class Subm extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $identifier = $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $subm = new \Gedcom\Record\Subm();
        $subm->setSubm($identifier);

        $parser->getGedcom()->addSubm($subm);

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType = strtoupper(trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'NAME':
                    $subm->setName(isset($record[2]) ? trim((string) $record[2]) : '');
                    break;
                case 'ADDR':
                    $addr = \Gedcom\Parser\Addr::parse($parser);
                    $subm->setAddr($addr);
                    break;
                case 'PHON':
                    $phone = \Gedcom\Parser\Phon::parse($parser);
                    $subm->addPhon($phone);
                    break;
                case 'EMAIL':
                    $email = isset($record[2]) ? trim((string) $record[2]) : '';
                    $subm->addEmail($email);
                    break;
                case 'FAX':
                    $fax = isset($record[2]) ? trim((string) $record[2]) : '';
                    $subm->addFax($fax);
                    break;
                case 'WWW':
                    $www = isset($record[2]) ? trim((string) $record[2]) : '';
                    $subm->addWww($www);
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $subm->addNote($note);
                    }
                    break;
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    $subm->addObje($obje);
                    break;
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $subm->setChan($chan);
                    break;
                case 'RIN':
                    $subm->setRin(isset($record[2]) ? trim((string) $record[2]) : '');
                    break;
                case 'RFN':
                    $subm->setRfn(isset($record[2]) ? trim((string) $record[2]) : '');
                    break;
                case 'LANG':
                    $subm->addLang(isset($record[2]) ? trim((string) $record[2]) : '');
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $subm;
    }
}
