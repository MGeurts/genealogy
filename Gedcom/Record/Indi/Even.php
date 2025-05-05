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

namespace Gedcom\Record\Indi;

use Gedcom\Record;

/**
 * Class Even.
 */
class Even extends Record implements Record\Noteable, Record\Objectable, Record\Sourceable
{
    /**
     * @var array
     */
    public $ref = [];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $_attr;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var Even\Plac
     */
    protected $plac;

    /**
     * @var string
     */
    protected $caus;

    /**
     * @var string
     */
    protected $age;

    /**
     * @var Record\Addr
     */
    protected $addr;

    /**
     * @var array
     */
    protected $phon = [];

    /**
     * @var string
     */
    protected $agnc;

    /**
     * @var array
     */
    protected $obje = [];

    /**
     * @var array
     */
    protected $sour = [];

    /**
     * @var array
     */
    protected $note = [];

    /**
     * @var Record\Chan
     */
    protected $chan;

    /**
     * @return array
     */
    public function getPhon()
    {
        return $this->phon;
    }

    /**
     * @param  Record\Phon  $phon
     * @return Even
     */
    public function addPhon($phon = [])
    {
        $this->phon[] = $phon;

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
     * @param  Record\ObjeRef  $obje
     * @return Even
     */
    public function addObje($obje = [])
    {
        $this->obje[] = $obje;

        return $this;
    }

    /**
     * @return array
     */
    public function getSour()
    {
        return $this->sour;
    }

    /**
     * @param  Record\SourRef  $sour
     * @return Even
     */
    public function addSour($sour = [])
    {
        $this->sour[] = $sour;

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
     * @param  Record\NoteRef  $note
     * @return Even
     */
    public function addNote($note = [])
    {
        $this->note[] = $note;

        return $this;
    }

    /**
     * @param  Record\Addr  $addr
     * @return Even
     */
    public function setAddr($addr = [])
    {
        $this->addr = $addr;

        return $this;
    }

    /**
     * @return Record\Addr
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * @param  string  $age
     * @return Even
     */
    public function setAge($record)
    {
        if (isset($record[2])) {
            $this->age = mb_trim($record[2]);
        } else {
            $this->age = '';
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param  string  $agnc
     * @return Even
     */
    public function setAgnc($agnc = '')
    {
        $this->agnc = $agnc;

        return $this;
    }

    /**
     * @return string
     */
    public function getAgnc()
    {
        return $this->agnc;
    }

    /**
     * @param  string  $caus
     * @return Even
     */
    public function setCaus($caus = '')
    {
        $this->caus = $caus;

        return $this;
    }

    /**
     * @return string
     */
    public function getCaus()
    {
        return $this->caus;
    }

    /**
     * @param  string  $date
     * @return Even
     */
    public function setDate($date = '')
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param  Even\Plac  $plac
     * @return Even
     */
    public function setPlac($plac = [])
    {
        $this->plac = $plac;

        return $this;
    }

    /**
     * @return Even\Plac
     */
    public function getPlac()
    {
        return $this->plac;
    }

    /**
     * @param  string  $type
     * @return Even
     */
    public function setType($type = '')
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  array  $ref
     * @return Even
     */
    public function setRef($ref = '')
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * @return array
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @return Record\Chan
     */
    public function getChan()
    {
        return $this->chan;
    }

    /**
     * @param  Record\Chan  $chan
     * @return $this
     */
    public function setChan($chan = [])
    {
        $this->chan = $chan;

        return $this;
    }
}
