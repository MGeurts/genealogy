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

class Caln extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record     = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[2]);
        $depth      = (int) $record[0];

        $caln = new \PhpGedcom\Record\Caln();
        $caln->setCaln($identifier);

        $parser->forward();

        while (! $parser->eof()) {
            $record     = $parser->getCurrentLineRecord();
            $recordType = mb_strtolower(mb_trim($record[1]));
            $lineDepth  = (int) $record[0];

            if ($lineDepth <= $depth) {
                $parser->back();
                break;
            }

            if ($caln->hasAttribute($recordType)) {
                $caln->{'set' . $recordType}(mb_trim($record[2]));
            } else {
                $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $caln;
    }
}
