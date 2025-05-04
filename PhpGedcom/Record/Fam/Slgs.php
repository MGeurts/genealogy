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

namespace PhpGedcom\Record\Fam;

use \PhpGedcom\Record\Sourceable;
use \PhpGedcom\Record\Noteable;

/**
 *
 */
class Slgs extends \PhpGedcom\Record implements Sourceable, Noteable
{
    protected $_stat;
    protected $_date;
    protected $_plac;
    protected $_temp;

    /**
     *
     */
    protected $_sour = array();

    /**
     *
     */
    protected $_note = array();

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
    public function addNote(\PhpGedcom\Record\NoteRef $note)
    {
        $this->_note[] = $note;
    }
}
