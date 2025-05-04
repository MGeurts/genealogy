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
class Repo extends \PhpGedcom\Parser\Component
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

        $repo = new \PhpGedcom\Record\Repo();
        $repo->setRepo($identifier);

        $parser->getGedcom()->addRepo($repo);

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
                    $repo->setName(trim($record[2]));
                    break;
                case 'ADDR':
                    $addr = \PhpGedcom\Parser\Addr::parse($parser);
                    $repo->setAddr($addr);
                    break;
                case 'PHON':
                    $phon = \PhpGedcom\Parser\Phon::parse($parser);
                    $repo->addPhon($phon);
                    break;
                case 'NOTE':
                    $note = \PhpGedcom\Parser\NoteRef::parse($parser);
                    $repo->addNote($note);
                    break;
                case 'REFN':
                    $refn = \PhpGedcom\Parser\Refn::parse($parser);
                    $repo->addRefn($refn);
                    break;
                case 'CHAN':
                    $chan = \PhpGedcom\Parser\Chan::parse($parser);
                    $repo->setChan($chan);
                    break;
                case 'RIN':
                    $repo->setRin(trim($record[2]));
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $repo;
    }
}
