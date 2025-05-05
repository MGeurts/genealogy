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

class Sour extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record     = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth      = (int) $record[0];

        $sour = new \PhpGedcom\Record\Sour();
        $sour->setSour($identifier);

        $parser->getGedcom()->addSour($sour);

        $parser->forward();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType   = mb_strtoupper(mb_trim($record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'TITL':
                    $sour->setTitl($parser->parseMultilineRecord());
                    break;
                case 'RIN':
                    $sour->setRin(mb_trim($record[2]));
                    break;
                case 'AUTH':
                    $sour->setAuth($parser->parseMultilineRecord());
                    break;
                case 'TEXT':
                    $sour->setText($parser->parseMultilineRecord());
                    break;
                case 'PUBL':
                    $sour->setPubl($parser->parseMultilineRecord());
                    break;
                case 'ABBR':
                    $sour->setAbbr(mb_trim($record[2]));
                    break;
                case 'REPO':
                    $sour->setRepo(RepoRef::parse($parser));
                    break;
                case 'NOTE':
                    $note = NoteRef::parse($parser);
                    if ($note) {
                        $sour->addNote($note);
                    }
                    break;
                case 'DATA':
                    $sour->setData(Sour\Data::parse($parser));
                    break;
                case 'OBJE':
                    $obje = ObjeRef::parse($parser);
                    $sour->addObje($obje);
                    break;
                case 'REFN':
                    $refn = Refn::parse($parser);
                    $sour->addRefn($refn);
                    break;
                case 'CHAN':
                    $chan = Chan::parse($parser);
                    $sour->setChan($chan);
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $sour;
    }
}
