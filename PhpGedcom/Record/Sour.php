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
 * Class Sour
 * @package PhpGedcom\Record
 */
class Sour extends Record implements Noteable, Objectable
{
    /**
     * @var string
     */
    protected $sour;

    /**
     * @var Chan
     */
    protected $chan;

    /**
     * @var string
     */
    protected $titl;

    /**
     * @var string
     */
    protected $auth;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $publ;

    /**
     * @var Repo
     */
    protected $repo;

    /**
     * @var string
     */
    protected $abbr;

    /**
     * @var string
     */
    protected $rin;

    /**
     * @var array
     */
    protected $refn = array();

    /**
     * @var array
     */
    protected $note = array();

    /**
     * @var array
     */
    protected $obje = array();

    /**
     * @param string $sour
     * @return Sour
     */
    public function setSour($sour)
    {
        $this->sour = $sour;
        return $this;
    }

    /**
     * @return string
     */
    public function getSour()
    {
        return $this->sour;
    }

    /**
     * @param string $titl
     * @return Sour
     */
    public function setTitl($titl)
    {
        $this->titl = $titl;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitl()
    {
        return $this->titl;
    }

    /**
     * @param string $abbr
     * @return Sour
     */
    public function setAbbr($abbr)
    {
        $this->abbr = $abbr;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbbr()
    {
        return $this->abbr;
    }

    /**
     * @param string $auth
     * @return Sour
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param string $publ
     * @return Sour
     */
    public function setPubl($publ)
    {
        $this->publ = $publ;
        return $this;
    }

    /**
     * @return string
     */
    public function getPubl()
    {
        return $this->publ;
    }

    /**
     * @param \PhpGedcom\Record\Repo $repo
     * @return Sour
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
        return $this;
    }

    /**
     * @return \PhpGedcom\Record\Repo
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param string $text
     * @return Sour
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $data
     * @return Sour
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $rin
     * @return Sour
     */
    public function setRin($rin)
    {
        $this->rin = $rin;
        return $this;
    }

    /**
     * @return string
     */
    public function getRin()
    {
        return $this->rin;
    }

    /**
     * @param \PhpGedcom\Record\Chan $chan
     * @return Sour
     */
    public function setChan($chan)
    {
        $this->chan = $chan;
        return $this;
    }

    /**
     * @return \PhpGedcom\Record\Chan
     */
    public function getChan()
    {
        return $this->chan;
    }

    /**
     * @param Refn $refn
     * @return Sour
     */
    public function addRefn(Refn $refn)
    {
        $this->refn[] = $refn;
        return $this;
    }

    /**
     * @return array
     */
    public function getRefn()
    {
        return $this->refn;
    }

    /**
     * @param NoteRef $note
     * @return Sour
     */
    public function addNote(NoteRef $note)
    {
        $this->note[] = $note;
        return $this;
    }

    /**
     * @return array
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param ObjeRef $obje
     * @return Sour
     */
    public function addObje(ObjeRef $obje)
    {
        $this->obje[] = $obje;
        return $this;
    }

    /**
     * @return array
     */
    public function getObje()
    {
        return $this->obje;
    }
}
