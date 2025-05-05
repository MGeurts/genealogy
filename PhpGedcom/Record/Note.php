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

class Note extends \PhpGedcom\Record implements Sourceable
{
    protected $_id = null;

    protected $_chan = null;

    protected $_note = null;

    protected $_even = null;

    protected $_refn = [];

    protected $_rin = null;

    protected $_sour = [];

    public function addRefn(Refn $refn)
    {
        $this->_refn[] = $refn;
    }

    public function addSour(SourRef $sour)
    {
        $this->_sour[] = $sour;
    }
}
