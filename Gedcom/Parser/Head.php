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

namespace Gedcom\Parser;

class Head extends Component
{
    /**
     * @return \Gedcom\Record\Head
     */
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];
        if (isset($record[1])) {
            $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $head = new \Gedcom\Record\Head();

        $parser->getGedcom()->setHead($head);

        $parser->forward();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType   = mb_strtoupper(mb_trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'SOUR':
                    $sour = Head\Sour::parse($parser);
                    $head->setSour($sour);
                    break;
                case 'SUBM':
                    $head->setSubm($parser->normalizeIdentifier($record[2]));
                    break;
                case 'SUBN':
                    $head->setSubn($parser->normalizeIdentifier($record[2]));
                    break;
                case 'DEST':
                    $dest = Head\Dest::parse($parser);
                    $head->setDest($dest);
                    break;
                case 'FILE':
                    $head->setFile(mb_trim((string) $record[2]));
                    break;
                case 'COPR':
                    $head->setCopr(mb_trim((string) $record[2]));
                    break;
                case 'LANG':
                    $head->setLang(mb_trim((string) $record[2]));
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
                    $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $head;
    }
}
