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

class RepoRef extends Component
{
    public static function parse(\PhpGedcom\Parser $parser)
    {
        $record     = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[2]);
        $depth      = (int) $record[0];

        $repo = new \PhpGedcom\Record\RepoRef();
        $repo->setRepo($identifier);

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
                case 'CALN':
                    $repo->addCaln(Caln::parse($parser));
                    break;
                case 'NOTE':
                    $note = NoteRef::parse($parser);
                    if ($note) {
                        $repo->addNote($note);
                    }
                    break;
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $repo;
    }
}
