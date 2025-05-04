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

namespace PhpGedcom\Record\Sour;

use \PhpGedcom\Record\Noteable;

/**
 *
 */
class Data extends \PhpGedcom\Record implements Noteable
{
    protected $_even = array();
    protected $_agnc = null;
    protected $_date = null;

    protected $_text = array();

    /**
     *
     */
    protected $_note = array();

    /**
     *
     */
    public function addText($text)
    {
        $this->_text[] = $text;
    }

    /**
     *
     */
    public function addNote(\PhpGedcom\Record\NoteRef $note)
    {
        $this->_note[] = $note;
    }
}
