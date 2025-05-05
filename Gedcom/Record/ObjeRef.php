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

class ObjeRef extends \Gedcom\Record
{
    /**
     * @var bool
     */
    protected $_isRef = false;

    protected $_obje = null;

    protected $_form = null;

    protected $_titl = null;

    protected $_file = null;

    protected $_note = [];

    public function setIsReference($isReference = true)
    {
        $this->_isRef = $isReference;
    }

    public function getIsReference()
    {
        return $this->_isRef;
    }

    public function addNote(NoteRef $note)
    {
        $this->_note[] = $note;
    }
}
