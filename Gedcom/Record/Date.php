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

namespace Gedcom\Record;

/**
 * Class Date.
 */
class Date extends \Gedcom\Record
{
    /**
     * @var string
     */
    protected $date;

    private array $months = [
        'JAN' => 1, 'FEB' => 2, 'MAR' => 3, 'APR' => 4, 'MAY' => 5, 'JUN' => 6,
        'JUL' => 7, 'AUG' => 8, 'SEP' => 9, 'OCT' => 10, 'NOV' => 11, 'DEC' => 12,
    ];

    /**
     * @param string $date Date array
     *
     * @return Date
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Return month part of date.
     *
     * @return int|null
     */
    public function getMonth()
    {
        $record = explode(' ', $this->date);
        if ($this->isPrefix($record[0])) {
            unset($record[0]);
        }
        foreach ($record as $part) {
            if (isset($this->months[trim($part)])) {
                return $this->months[trim($part)];
            }
        }

        return null;
    }

    /**
     * Return year part of date.
     *
     * @return int|null
     */
    public function getYear()
    {
        if (empty($this->date)) {
            return 0;
        }

        $record = explode(' ', $this->date);
        if ($this->isPrefix($record[0])) {
            unset($record[0]);
        }

        return (int) end($record);
    }

    /**
     * Return day part of date.
     *
     * @return int|null
     */
    public function getDay()
    {
        $record = explode(' ', $this->date);
        if (isset($record[0]) && $record[0] !== '') {
            if ($this->isPrefix($record[0])) {
                unset($record[0]);
            }
            if ($record !== []) {
                $day = (int) reset($record);
                if ($day >= 1 && $day <= 31) {
                    return $day;
                }
            }
        }

        return null;
    }

    /**
     * Check if the first part is a prefix (eg 'BEF', 'ABT',).
     *
     * @param string $datePart Date part to be checked
     *
     * @return bool
     */
    private function isPrefix($datePart)
    {
        return in_array($datePart, ['FROM', 'TO', 'BEF', 'AFT', 'BET', 'AND', 'ABT', 'EST', 'CAL', 'INT']);
    }
}
