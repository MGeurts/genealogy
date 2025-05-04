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

namespace Gedcom\Record\Indi;

use Gedcom\Record\Noteable;
use Gedcom\Record\Sourceable;

class Asso extends \Gedcom\Record implements Sourceable, Noteable
{
    protected $_indi;
    protected $_rela;

    protected $_note = [];

    protected $_sour = [];

    public function addNote($note = [])
    {
        $this->_note[] = $note;
    }

    public function addSour($sour = [])
    {
        $this->_sour[] = $sour;
    }
}
