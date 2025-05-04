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

/**
 *
 *
 */
class Fam extends \PhpGedcom\Record implements Noteable, Sourceable, Objectable
{
    /**
     *
     */
    protected $_id   = null;

    /**
     *
     */
    protected $_chan = null;

    /**
     *
     */
    protected $_husb = null;

    /**
     *
     */
    protected $_wife = null;

    /**
     *
     */
    protected $_nchi = null;

    /**
     *
     */
    protected $_chil = array();

    /**
     *
     */
    protected $_even = array();

    /**
     *
     */
    protected $_slgs = array();

    /**
     *
     */
    protected $_subm = array();

    /**
     *
     */
    protected $_refn = array();

    /**
     *
     */
    protected $_rin  = null;

    /**
     *
     */
    protected $_note = array();

    /**
     *
     */
    protected $_sour = array();

    /**
     *
     */
    protected $_obje = array();

    /**
     *
     */
    public function addEven(\PhpGedcom\Record\Fam\Even $even)
    {
        $this->_even[] = $even;
    }

    /**
     *
     */
    public function addSlgs(\PhpGedcom\Record\Fam\Slgs $slgs)
    {
        $this->_slgs[] = $slgs;
    }

    /**
     *
     *
     */
    public function addRefn(\PhpGedcom\Record\Refn $refn)
    {
        $this->_refn[] = $refn;
    }

    /**
     *
     */
    public function addNote(\PhpGedcom\Record\NoteRef $note)
    {
        $this->_note[] = $note;
    }

    /**
     *
     */
    public function addSour(\PhpGedcom\Record\SourRef $sour)
    {
        $this->_sour[] = $sour;
    }

    /**
     *
     */
    public function addObje(\PhpGedcom\Record\ObjeRef $obje)
    {
        $this->_obje[] = $obje;
    }
}
