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

class Note extends \Gedcom\Record implements Sourceable
{
    protected $_id;

    protected $_note;

    protected $_even;

    protected $_refn = [];

    protected $_rin;

    protected $_sour = [];

    protected $_chan;

    public function addRefn($refn = [])
    {
        $this->_refn[] = $refn;
    }

    public function addSour($sour = [])
    {
        $this->_sour[] = $sour;
    }
}
