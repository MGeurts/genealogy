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

namespace PhpGedcom\Record\Indi;

use PhpGedcom\Record\Noteable;
use PhpGedcom\Record\Objectable;
use PhpGedcom\Record\Sourceable;

class Attr extends Even implements Noteable, Objectable, Sourceable
{
    protected $type = null;

    protected $_attr = null;

    protected $sour = [];

    protected $note = [];

    protected $obje = [];

    public function addSour(\PhpGedcom\Record\SourRef $sour)
    {
        $this->sour[] = $sour;
    }

    public function addNote(\PhpGedcom\Record\NoteRef $note)
    {
        $this->note[] = $note;
    }

    public function addObje(\PhpGedcom\Record\ObjeRef $obje)
    {
        $this->obje[] = $obje;
    }
}
