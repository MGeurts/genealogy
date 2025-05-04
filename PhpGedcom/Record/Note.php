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
 *
 */
class Note extends \PhpGedcom\Record implements Sourceable
{
    protected $_id   = null;
    protected $_chan = null;

    protected $_note = null;
    protected $_even = null;
    protected $_refn = array();
    protected $_rin  = null;

    /**
     *
     */
    protected $_sour = array();

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
    public function addSour(\PhpGedcom\Record\SourRef $sour)
    {
        $this->_sour[] = $sour;
    }
}
