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

class Addr extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];
        $line   = isset($record[2]) ? mb_trim($record[2]) : '';

        $addr = new \PhpGedcom\Record\Addr();
        $addr->setAddr($line);
        $parser->forward();

        while (! $parser->eof()) {
            $record       = $parser->getCurrentLineRecord();
            $recordType   = mb_strtolower(mb_trim($record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            if ($addr->hasAttribute($recordType)) {
                $addr->{'set' . $recordType}(mb_trim($record[2]));
            } else {
                if ($recordType === 'cont') {
                    // FIXME: Can have CONT on multiple attributes
                    $addr->setAddr($addr->getAddr() . "\n");
                    if (isset($record[2])) {
                        $addr->setAddr($addr->getAddr() . mb_trim($record[2]));
                    }
                } else {
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                }
            }

            $parser->forward();
        }

        return $addr;
    }
}
