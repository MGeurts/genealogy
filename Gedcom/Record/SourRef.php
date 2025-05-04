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

namespace Gedcom\Record;

class SourRef extends \Gedcom\Record
{
    protected $_isRef = false;

    protected $_sour;
    protected $_page;
    protected $_even;
    protected $_data;
    protected $_quay;
    protected $_text;

    protected $_obje = [];
    protected $_note = [];

    public function setSour($sour = '')
    {
        $this->_sour = $sour;

        return $this;
    }
}
