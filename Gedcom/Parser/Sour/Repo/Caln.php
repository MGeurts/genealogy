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

namespace Gedcom\Parser\Sour\Repo;

class Caln extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $caln = new \Gedcom\Record\Sour\Repo\Caln();
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[2])) {
            $_caln = $record[2];
            $caln->setCaln($_caln);
        } else {
            return null;
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

            match ($recordType) {
                'MEDI' => $caln->setMedi(trim((string) $record[2])),
                default => $parser->logUnhandledRecord(self::class.' @ '.__LINE__),
            };

            $parser->forward();
        }

        return $caln;
    }
}
