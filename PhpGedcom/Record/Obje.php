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
 */
class Obje extends \PhpGedcom\Record implements Noteable
{
    protected $_id   = null;

    protected $_form = null;
    protected $_titl = null;
    protected $_blob = null;
    protected $_rin  = null;
    protected $_chan = null;

    protected $_refn = array();

    /**
     *
     */
    protected $_note = array();

    /**
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
}
