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

namespace Gedcom\Record\Fam;

use Gedcom\Record\Noteable;
use Gedcom\Record\Objectable;
use Gedcom\Record\Sourceable;

/**
 * Event record.
 *
 * @method mixed        getType()
 * @method \Record\Date getDate()
 * @method string       getPlac()
 */
class Even extends \Gedcom\Record implements Objectable, Sourceable, Noteable
{
    protected $_type;
    protected $_date;
    protected $_plac;
    protected $_caus;
    protected $_age;

    protected $_addr;

    protected $_phon = [];

    protected $_agnc;

    protected $_husb;
    protected $_wife;

    protected $_obje = [];

    protected $_sour = [];

    protected $_note = [];

    public function addPhon($phon = [])
    {
        $this->_phon[] = $phon;
    }

    public function addObje($obje = [])
    {
        $this->_obje[] = $obje;
    }

    public function addSour($sour = [])
    {
        $this->_sour[] = $sour;
    }

    public function addNote($note = [])
    {
        $this->_note[] = $note;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @return \PhpGedcom\Record\Indi\Even\Plac
     */
    public function getPlac()
    {
        return $this->_plac;
    }

    /**
     * @return array
     */
    public function getSour()
    {
        return $this->_sour;
    }

    /**
     * @return array
     */
    public function getNote()
    {
        return $this->_note;
    }

    /**
     * @return array
     */
    public function getObje()
    {
        return $this->_obje;
    }

    /**
     * @return \PhpGedcom\Record\Addr
     */
    public function getAddr()
    {
        return $this->_addr;
    }

    /**
     * @return string
     */
    public function getAge()
    {
        return $this->_age;
    }

    /**
     * @return string
     */
    public function getAgnc()
    {
        return $this->_agnc;
    }

    /**
     * @return string
     */
    public function getCaus()
    {
        return $this->_caus;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }
}
