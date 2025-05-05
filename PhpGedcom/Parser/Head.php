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

class Head extends Component
{
    /**
     * @return \PhpGedcom\Record\Head
     */
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record     = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth      = (int) $record[0];

        $head = new \PhpGedcom\Record\Head();

        $parser->getGedcom()->setHead($head);

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
                case 'SOUR':
                    $sour = Head\Sour::parse($parser);
                    $head->setSour($sour);
                    break;
                case 'DEST':
                    $head->setDest(mb_trim($record[2]));
                    break;
                case 'SUBM':
                    $head->setSubm($parser->normalizeIdentifier($record[2]));
                    break;
                case 'SUBN':
                    $head->setSubn($parser->normalizeIdentifier($record[2]));
                    break;
                case 'DEST':
                    $head->setDest(mb_trim($record[2]));
                    break;
                case 'FILE':
                    $head->setFile(mb_trim($record[2]));
                    break;
                case 'COPR':
                    $head->setCopr(mb_trim($record[2]));
                    break;
                case 'LANG':
                    $head->setLang(mb_trim($record[2]));
                    break;
                case 'DATE':
                    $date = Head\Date::parse($parser);
                    $head->setDate($date);
                    break;
                case 'GEDC':
                    $gedc = Head\Gedc::parse($parser);
                    $head->setGedc($gedc);
                    break;
                case 'CHAR':
                    $char = Head\Char::parse($parser);
                    $head->setChar($char);
                    break;
                case 'PLAC':
                    $plac = Head\Plac::parse($parser);
                    $head->setPlac($plac);
                    break;
                case 'NOTE':
                    $head->setNote($parser->parseMultiLineRecord());
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $head;
    }
}
