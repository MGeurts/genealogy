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

namespace PhpGedcom\Record;

use PhpGedcom\Record;

/**
 * Class Subm
 */
class Subm extends Record implements Objectable
{
    /**
     * @var string
     */
    protected $subm;

    /**
     * @var Chan
     */
    protected $chan;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Addr
     */
    protected $addr;

    /**
     * @var string
     */
    protected $rin;

    /**
     * @var string
     */
    protected $rfn;

    /**
     * @var array
     */
    protected $lang = [];

    /**
     * @var array
     */
    protected $phon = [];

    /**
     * @var array
     */
    protected $obje = [];

    /**
     * @param  string  $subm
     * @return Subm
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
     * @param  string  $name
     * @return Subm
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  array  $phon
     * @return Subm
     */
    public function setPhon($phon)
    {
        $this->phon = $phon;

        return $this;
    }

    /**
     * @return array
     */
    public function getPhon()
    {
        return $this->phon;
    }

    /**
     * @param  string  $rfn
     * @return Subm
     */
    public function setRfn($rfn)
    {
        $this->rfn = $rfn;

        return $this;
    }

    /**
     * @return string
     */
    public function getRfn()
    {
        return $this->rfn;
    }

    /**
     * @param  string  $rin
     * @return Subm
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
     * @param  Chan  $chan
     * @return Subm
     */
    public function setChan($chan)
    {
        $this->chan = $chan;

        return $this;
    }

    /**
     * @return Chan
     */
    public function getChan()
    {
        return $this->chan;
    }

    /**
     * @return array
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param  string  $lang
     * @return Subm
     */
    public function addLang($lang)
    {
        $this->lang[] = $lang;

        return $this;
    }

    /**
     * @return Subm
     */
    public function addPhon(Phon $phon)
    {
        $this->phon[] = $phon;

        return $this;
    }

    /**
     * @return Addr
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * @return Subm
     */
    public function setAddr(Addr $addr)
    {
        $this->addr = $addr;

        return $this;
    }

    /**
     * @return array
     */
    public function getObje()
    {
        return $this->obje;
    }

    /**
     * @return Subm
     */
    public function addObje(ObjeRef $obje)
    {
        $this->obje[] = $obje;

        return $this;
    }
}
