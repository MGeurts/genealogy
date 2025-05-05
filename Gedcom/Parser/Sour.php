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

class Sour extends Component
    /**
     * Parser for GEDCOM Source (SOUR) records.
     *
     * Handles the parsing of source records from GEDCOM files, extracting relevant data and attributes
     * associated with sources.
     */
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

        $sour = new \Gedcom\Record\Sour();
        $sour->setSour($identifier);

        $parser->getGedcom()->addSour($sour);

        $parser->forward();

        while (! $parser->eof()) {
            /**
             * Sour class for parsing source records.
             *
             * This class extends the Component class and provides functionality to parse source (SOUR) records
             * from a GEDCOM file.
             */
            /**
             * Parses a source record from a GEDCOM file.
             *
             * @param  \Gedcom\Parser  $parser  The parser instance.
             * @return \Gedcom\Record\Sour|null The parsed source record, or null if parsing fails.
             */
            $record       = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType   = mb_strtoupper(mb_trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                /**
                 * Parses the source record's substructures and attributes.
                 *
                 * Iterates through the lines of the source record, parsing its substructures like DATA, AUTH, TITL, etc.,
                 * based on the current depth and record type.
                 */
                case 'DATA':
                    $sour->setData(Sour\Data::parse($parser));
                    break;
                case 'AUTH':
                    $sour->setAuth($parser->parseMultilineRecord());
                    break;
                case 'TITL':
                    $sour->setTitl($parser->parseMultilineRecord());
                    break;
                case 'ABBR':
                    $sour->setAbbr(mb_trim((string) $record[2]));
                    break;
                case 'PUBL':
                    $sour->setPubl($parser->parseMultilineRecord());
                    break;
                case 'TEXT':
                    /**
                     * Parses specific substructures of the source record.
                     *
                     * Depending on the record type, it delegates parsing to specialized methods or directly sets attributes
                     * of the source record.
                     */
                    $sour->setText($parser->parseMultilineRecord());
                    break;
                case 'REPO':
                    $sour->setRepo(Sour\Repo::parse($parser));
                    break;
                case 'REFN':
                    $refn = Refn::parse($parser);
                    $sour->addRefn($refn);
                    break;
                case 'RIN':
                    $sour->setRin(mb_trim((string) $record[2]));
                    break;
                case 'CHAN':
                    $chan = Chan::parse($parser);
                    $sour->setChan($chan);
                    break;
                case 'NOTE':
                    /**
                     * Continues parsing specific substructures of the source record.
                     *
                     * Handles additional record types like TEXT, REPO, REFN, and sets their corresponding attributes
                     * in the source record.
                     */
                    $note = NoteRef::parse($parser);
                    if ($note) {
                        $sour->addNote($note);
                    }
                    break;
                case 'OBJE':
                    $obje = ObjeRef::parse($parser);
                    $sour->addObje($obje);
                    break;
                default:
                    $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $sour;
    }
}
/**
 * Finalizes parsing of the source record.
 *
 * Parses the remaining record types like NOTE, OBJE, and handles unhandled records. Returns the fully
 * parsed source record.
 */
