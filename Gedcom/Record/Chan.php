<?php

declare(strict_types=1);

namespace Gedcom\Record;

class Chan extends \Gedcom\Record
{
    private const MONTHS = [
        'JAN' => '01', 'FEB' => '02', 'MAR' => '03', 'APR' => '04',
        'MAY' => '05', 'JUN' => '06', 'JUL' => '07', 'AUG' => '08',
        'SEP' => '09', 'OCT' => '10', 'NOV' => '11', 'DEC' => '12',
    ];

    private string $date = '';

    private string $time = '';

    private string $datetime = '';

    private array $note = [];

    public function setDate(string $date = ''): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param  Record\NoteRef  $note
     */
    public function addNote(NoteRef $note): self
    {
        $this->note[] = $note;

        return $this;
    }

    public function getNote(): array
    {
        return $this->note;
    }

    public function setTime(string $time = ''): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setDatetime(string $date = ''): self
    {
        $this->datetime = $date . ' ' . $this->time;

        return $this;
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function getMonth()
    {
        $record = explode(' ', $this->date);
        if ($this->isPrefix($record[0])) {
            unset($record[0]);
        }
        foreach ($record as $part) {
            if (isset($this->MONTHS[mb_trim($part)])) {
                return $this->MONTHS[mb_trim($part)];
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
                    return mb_substr("0{$day}", -2);
                }
            }
        }

        return null;
    }

    /**
     * Check if the first part is a prefix (eg 'BEF', 'ABT',).
     *
     * @param  string  $datePart  Date part to be checked
     * @return bool
     */
    private function isPrefix($datePart)
    {
        return in_array($datePart, ['FROM', 'TO', 'BEF', 'AFT', 'BET', 'AND', 'ABT', 'EST', 'CAL', 'INT']);
    }
}
