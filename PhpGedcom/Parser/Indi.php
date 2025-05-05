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

namespace PhpGedcom\Parser;

class Indi extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record     = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth      = (int) $record[0];

        $indi = new \PhpGedcom\Record\Indi();
        $indi->setId($identifier);

        $parser->getGedcom()->addIndi($indi);

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
                case 'NAME':
                    $name = Indi\Name::parse($parser);
                    $indi->addName($name);
                    break;
                case 'ALIA':
                    $indi->addAlia($parser->normalizeIdentifier($record[2]));
                    break;
                case 'SEX':
                    $indi->setSex(mb_trim($record[2]));
                    break;
                case 'RIN':
                    $indi->setRin(mb_trim($record[2]));
                    break;
                case 'RESN':
                    $indi->setResn(mb_trim($record[2]));
                    break;
                case 'RFN':
                    $indi->setRfn(mb_trim($record[2]));
                    break;
                case 'AFN':
                    $indi->setAfn(mb_trim($record[2]));
                    break;
                case 'CHAN':
                    $chan = Chan::parse($parser);
                    $indi->setChan($chan);
                    break;
                case 'FAMS':
                    $fams = Indi\Fams::parse($parser);
                    if ($fams) {
                        $indi->addFams($fams);
                    }
                    break;
                case 'FAMC':
                    $famc = Indi\Famc::parse($parser);
                    if ($famc) {
                        $indi->addFamc($famc);
                    }
                    break;
                case 'ASSO':
                    $asso = Indi\Asso::parse($parser);
                    $indi->addAsso($asso);
                    break;
                case 'ANCI':
                    $indi->addAnci($parser->normalizeIdentifier($record[2]));
                    break;
                case 'DESI':
                    $indi->addDesi($parser->normalizeIdentifier($record[2]));
                    break;
                case 'SUBM':
                    $indi->addSubm($parser->normalizeIdentifier($record[2]));
                    break;
                case 'REFN':
                    $ref = Refn::parse($parser);
                    $indi->addRefn($ref);
                    break;
                case 'BAPL':
                case 'CONL':
                case 'ENDL':
                case 'SLGC':
                    $className = ucfirst(mb_strtolower($recordType));
                    $class     = '\\PhpGedcom\\Parser\\Indi\\' . $className;

                    $lds = $class::parse($parser);
                    $indi->{'set' . $recordType}($lds);
                    break;
                case 'OBJE':
                    $obje = ObjeRef::parse($parser);
                    $indi->addObje($obje);
                    break;
                case 'NOTE':
                    $note = NoteRef::parse($parser);
                    if ($note) {
                        $indi->addNote($note);
                    }
                    break;
                case 'SOUR':
                    $sour = SourRef::parse($parser);
                    $indi->addSour($sour);
                    break;
                case 'ADOP':
                case 'BIRT':
                case 'BAPM':
                case 'BARM':
                case 'BASM':
                case 'BLES':
                case 'BURI':
                case 'CENS':
                case 'CHR':
                case 'CHRA':
                case 'CONF':
                case 'CREM':
                case 'DEAT':
                case 'EMIG':
                case 'FCOM':
                case 'GRAD':
                case 'IMMI':
                case 'NATU':
                case 'ORDN':
                case 'RETI':
                case 'PROB':
                case 'WILL':
                case 'EVEN':
                    $className = ucfirst(mb_strtolower($recordType));
                    $class     = '\\PhpGedcom\\Parser\\Indi\\' . $className;

                    $event = $class::parse($parser);
                    $indi->addEven($event);
                    break;
                case 'CAST':
                case 'DSCR':
                case 'EDUC':
                case 'IDNO':
                case 'NATI':
                case 'NCHI':
                case 'NMR':
                case 'OCCU':
                case 'PROP':
                case 'RELI':
                case 'RESI':
                case 'SSN':
                case 'TITL':
                    $className = ucfirst(mb_strtolower($recordType));
                    $class     = '\\PhpGedcom\\Parser\\Indi\\' . $className;

                    $att = $class::parse($parser);
                    $indi->addAttr($att);
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $indi;
    }
}
