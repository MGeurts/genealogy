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

namespace PhpGedcom\Record\Indi;

use \PhpGedcom\Record\Sourceable;
use \PhpGedcom\Record\Noteable;
use \PhpGedcom\Record\Objectable;

/**
 *
 */
class Attr extends \PhpGedcom\Record\Indi\Even implements Sourceable, Noteable, Objectable
{
    protected $type = null;
    protected $_attr = null;

    /**
     *
     */
    protected $sour = array();

    /**
     *
     */
    protected $note = array();

    /**
     *
     */
    protected $obje = array();

    /**
     *
     */
    public function addSour(\PhpGedcom\Record\SourRef $sour)
    {
        $this->sour[] = $sour;
    }

    /**
     *
     */
    public function addNote(\PhpGedcom\Record\NoteRef $note)
    {
        $this->note[] = $note;
    }

    /**
     *
     */
    public function addObje(\PhpGedcom\Record\ObjeRef $obje)
    {
        $this->obje[] = $obje;
    }
}
