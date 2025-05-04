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

namespace PhpGedcom\Record\Head;

/**
 *
 */
class Sour extends \PhpGedcom\Record
{
    /**
     *
     */
    protected $_sour = null;

    /**
     *
     */
    protected $_vers = null;

    /**
     *
     */
    protected $_name = null;

    /**
     *
     */
    protected $_corp = null;

    /**
     *
     */
    protected $_data = null;

    /**
     *
     * @param Sour\Corp $corp
     */
    public function setCorp(\PhpGedcom\Record\Head\Sour\Corp $corp)
    {
        $this->_corp = $corp;
    }

    /**
     *
     * @return Sour\Corp
     */
    public function getCorp()
    {
        return $this->_corp;
    }

    /**
     *
     * @param \PhpGedcom\Record\Head\Sour\Data $data
     */
    public function setData(\PhpGedcom\Record\Head\Sour\Data $data)
    {
        $this->_data = $data;
    }

    /**
     *
     * @return \PhpGedcom\Record\Head\Sour\Data
     */
    public function getData()
    {
        return $this->_data;
    }
}
