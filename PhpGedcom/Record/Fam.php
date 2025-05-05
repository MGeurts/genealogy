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

class Fam extends \PhpGedcom\Record implements Noteable, Objectable, Sourceable
{
    protected $_id = null;

    protected $_chan = null;

    protected $_husb = null;

    protected $_wife = null;

    protected $_nchi = null;

    protected $_chil = [];

    protected $_even = [];

    protected $_slgs = [];

    protected $_subm = [];

    protected $_refn = [];

    protected $_rin = null;

    protected $_note = [];

    protected $_sour = [];

    protected $_obje = [];

    public function addEven(Fam\Even $even)
    {
        $this->_even[] = $even;
    }

    public function addSlgs(Fam\Slgs $slgs)
    {
        $this->_slgs[] = $slgs;
    }

    public function addRefn(Refn $refn)
    {
        $this->_refn[] = $refn;
    }

    public function addNote(NoteRef $note)
    {
        $this->_note[] = $note;
    }

    public function addSour(SourRef $sour)
    {
        $this->_sour[] = $sour;
    }

    public function addObje(ObjeRef $obje)
    {
        $this->_obje[] = $obje;
    }
}
