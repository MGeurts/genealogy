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

class Repo extends Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth  = (int) $record[0];
        if (isset($record[1])) {
            $identifier = $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $repo = new \Gedcom\Record\Repo();
        $repo->setRepo($identifier);

        $parser->getGedcom()->addRepo($repo);

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
                case 'NAME':
                    $repo->setName(mb_trim((string) $record[2]));
                    break;
                case 'ADDR':
                    $addr = Addr::parse($parser);
                    $repo->setAddr($addr);
                    break;
                case 'PHON':
                    $repo->addPhon(mb_trim((string) $record[2]));
                    break;
                case 'EMAIL':
                    $repo->addEmail(mb_trim((string) $record[2]));
                    break;
                case 'FAX':
                    $repo->addFax(mb_trim((string) $record[2]));
                    break;
                case 'WWW':
                    $repo->addWww(mb_trim((string) $record[2]));
                    break;
                case 'NOTE':
                    if ($note = NoteRef::parse($parser)) {
                        $repo->addNote($note);
                    }
                    break;
                case 'REFN':
                    $refn = Refn::parse($parser);
                    $repo->addRefn($refn);
                    break;
                case 'RIN':
                    $repo->setRin(mb_trim((string) $record[2]));
                    break;
                case 'CHAN':
                    $chan = Chan::parse($parser);
                    $repo->setChan($chan);
                    break;
                default:
                    $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $repo;
    }
}
