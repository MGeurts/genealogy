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

namespace PhpGedcom;

/**
 *
 */
class Parser
{
    /**
     *
     */
    protected $_file            = null;

    /**
     *
     */
    protected $_gedcom          = null;

    /**
     *
     */
    protected $_errorLog        = array();

    /**
     *
     */
    protected $_linesParsed     = 0;

    /**
     *
     */
    protected $_line            = '';

    /**
     *
     */
    protected $_lineRecord      = null;

    /**
     *
     */
    protected $_returnedLine    = '';

    /**
     *
     */
    public function __construct(\PhpGedcom\Gedcom $gedcom = null)
    {
        if (!is_null($gedcom)) {
            $this->_gedcom = $gedcom;
        } else {
            $this->_gedcom = new \PhpGedcom\Gedcom();
        }
    }

    /**
     *
     */
    public function forward()
    {
        // if there was a returned line by back(), set that as our current
        // line and blank out the returnedLine variable, otherwise grab
        // the next line from the file

        if (!empty($this->_returnedLine)) {
            $this->_line = $this->_returnedLine;
            $this->_returnedLine = '';
        } else {
            $this->_line = fgets($this->_file);
            $this->_lineRecord = null;
            $this->_linesParsed++;
        }

        return $this;
    }

    /**
     *
     */
    public function back()
    {
        // our parser object encountered a line it wasn't meant to parse
        // store this line for the previous parser to analyze

        $this->_returnedLine = $this->_line;

        return $this;
    }

    /**
     * Jump to the next level in the GEDCOM that is <= $level. This will leave the parser at the line above
     * this level, such that calling $parser->forward() will result in landing at the correct level.
     *
     * @param int $level
     */
    public function skipToNextLevel($level)
    {
        $currentDepth = 999;

        while ($currentDepth > $level) {
            $this->forward();
            $record = $this->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
        }

        $this->back();
    }

    /**
     *
     */
    public function getGedcom()
    {
        return $this->_gedcom;
    }

    /**
     *
     */
    public function eof()
    {
        return feof($this->_file);
    }

    /**
     *
     * @return string
     */
    public function parseMultiLineRecord()
    {
        $record = $this->getCurrentLineRecord();

        $depth = (int) $record[0];
        $data = isset($record[2]) ? trim($record[2]) : '';

        $this->forward();

        while (!$this->eof()) {
            $record = $this->getCurrentLineRecord();
            $recordType = strtoupper(trim($record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $this->back();
                break;
            }

            switch ($recordType) {
                case 'CONT':
                    $data .= "\n";

                    if (isset($record[2])) {
                        $data .= trim($record[2]);
                    }
                    break;
                case 'CONC':
                    if (isset($record[2])) {
                        $data .= ' ' . trim($record[2]);
                    }
                    break;
                default:
                    $this->back();
                    break 2;
            }

            $this->forward();
        }

        return $data;
    }

    /**
     *
     * @return string The current line
     */
    public function getCurrentLine()
    {
        return $this->_line;
    }

    /**
     *
     */
    public function getCurrentLineRecord($pieces = 3)
    {
        if (!is_null($this->_lineRecord)) {
            return $this->_lineRecord;
        }

        if (empty($this->_line)) {
            return false;
        }

        $line = trim($this->_line);

        $this->_lineRecord = explode(' ', $line, $pieces);

        return $this->_lineRecord;
    }

    /**
     *
     */
    protected function logError($error)
    {
        $this->_errorLog[] = $error;
    }

    /**
     *
     */
    public function logUnhandledRecord($additionalInfo = '')
    {
        $this->logError(
            $this->_linesParsed . ': (Unhandled) ' . trim(implode('|', $this->getCurrentLineRecord())) .
                (!empty($additionalInfo) ? ' - ' . $additionalInfo : '')
        );
    }

    public function logSkippedRecord($additionalInfo = '')
    {
        $this->logError(
            $this->_linesParsed . ': (Skipping) ' . trim(implode('|', $this->getCurrentLineRecord())) .
                (!empty($additionalInfo) ? ' - ' . $additionalInfo : '')
        );
    }

    /**
     *
     */
    public function getErrors()
    {
        return $this->_errorLog;
    }

    /**
     *
     */
    public function normalizeIdentifier($identifier)
    {
        $identifier = trim($identifier);
        $identifier = trim($identifier, '@');

        return $identifier;
    }

    /**
     *
     * @param string $fileName
     * @return Gedcom
     */
    public function parse($fileName)
    {
        $this->_file = fopen($fileName, 'r'); #explode("\n", mb_convert_encoding($contents, 'UTF-8'));

        if (!$this->_file) {
            return null;
        }

        $this->forward();

        while (!$this->eof()) {
            $record = $this->getCurrentLineRecord();

            if ($record === false) {
                continue;
            }

            $depth = (int) $record[0];

            // We only process 0 level records here. Sub levels are processed
            // in methods for those data types (individuals, sources, etc)

            if ($depth == 0) {
                // Although not always an identifier (HEAD,TRLR):
                $identifier = $this->normalizeIdentifier($record[1]);

                if (trim($record[1]) == 'HEAD') {
                    Parser\Head::parse($this);
                } else if (isset($record[2]) && trim($record[2]) == 'SUBN') {
                    Parser\Subn::parse($this);
                } else if (isset($record[2]) && trim($record[2]) == 'SUBM') {
                    Parser\Subm::parse($this);
                } else if (isset($record[2]) && $record[2] == 'SOUR') {
                    Parser\Sour::parse($this);
                } else if (isset($record[2]) && $record[2] == 'INDI') {
                    Parser\Indi::parse($this);
                } else if (isset($record[2]) && $record[2] == 'FAM') {
                    Parser\Fam::parse($this);
                } else if (isset($record[2]) && substr(trim($record[2]), 0, 4) == 'NOTE') {
                    Parser\Note::parse($this);
                } else if (isset($record[2]) && $record[2] == 'REPO') {
                    Parser\Repo::parse($this);
                } else if (isset($record[2]) && $record[2] == 'OBJE') {
                    Parser\Obje::parse($this);
                } else if (trim($record[1]) == 'TRLR') {
                    // EOF
                    break;
                } else {
                    $this->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                }
            } else {
                $this->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }

            $this->forward();
        }

        return $this->getGedcom();
    }
}
