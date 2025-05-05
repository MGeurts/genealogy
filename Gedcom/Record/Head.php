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

namespace Gedcom\Record;

/**
 * Stores the data from the HEAD section of a GEDCOM 5.5 file.
 */
class Head extends \Gedcom\Record
{
    /**
     * @var Head\Sour
     */
    protected $sour;

    /**
     * @var string
     */
    protected $dest;

    /**
     * @var Head\Date
     */
    protected $date;

    /**
     * @var string
     */
    protected $subm;

    /**
     * @var string
     */
    protected $subn;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $copr;

    /**
     * @var Head\Gedc
     */
    protected $gedc;

    /**
     * @var Head\Char
     */
    protected $char;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var Head\Plac
     */
    protected $plac;

    /**
     * @var string
     */
    protected $note;

    /**
     * @param  Head\Sour  $sour
     * @return Head
     */
    public function setSour($sour = [])
    {
        $this->sour = $sour;

        return $this;
    }

    /**
     * @return Head\Sour
     */
    public function getSour()
    {
        return $this->sour;
    }

    /**
     * @param  Head\Date  $date
     * @return Head
     */
    public function setDate($date = [])
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Head\Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param  Head\Gedc  $gedc
     * @return Head
     */
    public function setGedc($gedc = [])
    {
        $this->gedc = $gedc;

        return $this;
    }

    /**
     * @return Head\Gedc
     */
    public function getGedc()
    {
        return $this->gedc;
    }

    /**
     * @param  Head\Char  $char
     * @return Head
     */
    public function setChar($char = [])
    {
        $this->char = $char;

        return $this;
    }

    /**
     * @return Head\Char
     */
    public function getChar()
    {
        return $this->char;
    }

    /**
     * @param  Head\Plac  $plac
     * @return Head
     */
    public function setPlac($plac = [])
    {
        $this->plac = $plac;

        return $this;
    }

    /**
     * @return Head\Plac
     */
    public function getPlac()
    {
        return $this->plac;
    }

    /**
     * @param  string  $subm
     * @return Head
     */
    public function setSubm($subm = '')
    {
        $this->subm = $subm;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubm()
    {
        return $this->subm;
    }

    /**
     * @param  string  $subn
     * @return Head
     */
    public function setSubn($subn = '')
    {
        $this->subn = $subn;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubn()
    {
        return $this->subn;
    }

    /**
     * @param  string  $lang
     * @return Head
     */
    public function setLang($lang = '')
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param  string  $file
     * @return Head
     */
    public function setFile($file = '')
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param  string  $dest
     * @return Head
     */
    public function setDest($dest = '')
    {
        $this->dest = $dest;

        return $this;
    }

    /**
     * @return string
     */
    public function getDest()
    {
        return $this->dest;
    }

    /**
     * @param  string  $copr
     * @return Head
     */
    public function setCopr($copr = '')
    {
        $this->copr = $copr;

        return $this;
    }

    /**
     * @return string
     */
    public function getCopr()
    {
        return $this->copr;
    }

    /**
     * @param  string  $note
     * @return Head
     */
    public function setNote($note = '')
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
