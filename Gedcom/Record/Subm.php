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

use Gedcom\Record;

/**
 * Class Subm.
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
    protected $email = [];

    /**
     * @var array
     */
    protected $fax = [];

    /**
     * @var array
     */
    protected $www = [];

    /**
     * @var array
     */
    protected $obje = [];

    /**
     * @var array
     */
    protected $note = [];

    /**
     * @param  string  $subm
     * @return Subm
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
     * @param  string  $name
     * @return Subm
     */
    public function setName($name = '')
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
    public function setPhon($phon = [])
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
     * @param  Phon  $phon
     * @return Subm
     */
    public function addPhon($phon = [])
    {
        $this->phon[] = $phon;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param  Phon  $phon
     * @return Subm
     */
    public function addEmail($email)
    {
        $this->email[] = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param  Phon  $phon
     * @return Subm
     */
    public function addFax($fax)
    {
        $this->fax[] = $fax;

        return $this;
    }

    /**
     * @return array
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * @param  Phon  $phon
     * @return Subm
     */
    public function addWww($www)
    {
        $this->www[] = $www;

        return $this;
    }

    /**
     * @param  string  $rfn
     * @return Subm
     */
    public function setRfn($rfn = '')
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
    public function setRin($rin = '')
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
    public function setChan($chan = [])
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
    public function addLang($lang = '')
    {
        $this->lang[] = $lang;

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
     * @param  Addr  $addr
     * @return Subm
     */
    public function setAddr($addr = [])
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
     * @param  ObjeRef  $obje
     * @return Subm
     */
    public function addObje($obje)
    {
        $this->obje[] = $obje;

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
     * @param  Note  $note
     * @return Subm
     */
    public function addNote($note = [])
    {
        $this->note[] = $note;

        return $this;
    }
}
