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

namespace Gedcom\Record\Sour;

use Gedcom\Record\Noteable;

class Repo extends \Gedcom\Record implements Noteable
{
    protected $_repo;

    /**
     * array PhpRecord\Sour\Repo\Caln.
     */
    protected $_caln = [];

    /**
     * array PhpRecord\NoteRef.
     */
    protected $_note = [];

    public function addNote($note = [])
    {
        $this->_note[] = $note;
    }

    public function addCaln($caln = [])
    {
        $this->_caln[] = $caln;
    }
}
