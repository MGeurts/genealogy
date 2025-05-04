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
 * Class Subn
 * @package PhpGedcom\Record
 */
class Subn extends Record
{
    /**
     * @var string
     */
    protected $subn;

    /**
     * @var string
     */
    protected $subm;

    /**
     * @var string
     */
    protected $famf;

    /**
     * @var string
     */
    protected $temp;

    /**
     * @var string
     */
    protected $ance;

    /**
     * @var string
     */
    protected $desc;

    /**
     * @var string
     */
    protected $ordi;

    /**
     * @var string
     */
    protected $rin;

    /**
     * @param string $subn
     * @return Subn
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
     * @param string $subm
     * @return Subn
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
     * @param string $famf
     * @return Subn
     */
    public function setFamf($famf)
    {
        $this->famf = $famf;
        return $this;
    }

    /**
     * @return string
     */
    public function getFamf()
    {
        return $this->famf;
    }

    /**
     * @param string $temp
     * @return Subn
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * @param string $ance
     * @return Subn
     */
    public function setAnce($ance)
    {
        $this->ance = $ance;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnce()
    {
        return $this->ance;
    }

    /**
     * @param string $desc
     * @return Subn
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param string $ordi
     * @return Subn
     */
    public function setOrdi($ordi)
    {
        $this->ordi = $ordi;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrdi()
    {
        return $this->ordi;
    }

    /**
     * @param string $rin
     * @return Subn
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
}
