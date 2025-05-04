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

namespace PhpGedcom\Record;

use PhpGedcom\Record;

/**
 * Stores the data from the HEAD section of a GEDCOM 5.5 file.
 */
class Head extends Record
{
    /**
     * @var Head\Sour
     */
    protected $sour = null;

    /**
     * @var string
     */
    protected $dest = null;

    /**
     * @var Head\Date
     */
    protected $date = null;

    /**
     * @var string
     */
    protected $subm = null;

    /**
     * @var string
     */
    protected $subn = null;

    /**
     * @var string
     */
    protected $file = null;

    /**
     * @var string
     */
    protected $copr = null;

    /**
     * @var Head\Gedc
     */
    protected $gedc = null;

    /**
     * @var Head\Char
     */
    protected $char = null;

    /**
     * @var string
     */
    protected $lang = null;

    /**
     * @var Head\Plac
     */
    protected $plac = null;

    /**
     * @var string
     */
    protected $note = null;

    /**
     * @param \PhpGedcom\Record\Head\Sour $sour
     * @return Head
     */
    public function setSour(Head\Sour $sour)
    {
        $this->sour = $sour;
        return $this;
    }

    /**
     * @return \PhpGedcom\Record\Head\Sour
     */
    public function getSour()
    {
        return $this->sour;
    }

    /**
     * @param \PhpGedcom\Record\Head\Date $date
     * @return Head
     */
    public function setDate(Head\Date $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \PhpGedcom\Record\Head\Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \PhpGedcom\Record\Head\Gedc $gedc
     * @return Head
     */
    public function setGedc(Head\Gedc $gedc)
    {
        $this->gedc = $gedc;
        return $this;
    }

    /**
     * @return \PhpGedcom\Record\Head\Gedc
     */
    public function getGedc()
    {
        return $this->gedc;
    }

    /**
     * @param \PhpGedcom\Record\Head\Char $char
     * @return Head
     */
    public function setChar(Head\Char $char)
    {
        $this->char = $char;
        return $this;
    }

    /**
     * @return \PhpGedcom\Record\Head\Char
     */
    public function getChar()
    {
        return $this->char;
    }

    /**
     * @param \PhpGedcom\Record\Head\Plac $plac
     * @return Head
     */
    public function setPlac(Head\Plac $plac)
    {
        $this->plac = $plac;
        return $this;
    }

    /**
     * @return \PhpGedcom\Record\Head\Plac
     */
    public function getPlac()
    {
        return $this->plac;
    }

    /**
     * @param string $subm
     * @return Head
     */
    public function setSubm($subm)
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
     * @param string $subn
     * @return Head
     */
    public function setSubn($subn)
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
     * @param string $lang
     * @return Head
     */
    public function setLang($lang)
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
     * @param string $file
     * @return Head
     */
    public function setFile($file)
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
     * @param string $dest
     * @return Head
     */
    public function setDest($dest)
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
     * @param string $copr
     * @return Head
     */
    public function setCopr($copr)
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
     * @param string $note
     * @return Head
     */
    public function setNote($note)
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
