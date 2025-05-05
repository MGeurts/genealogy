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

namespace PhpGedcom\Record\Head;

class Sour extends \PhpGedcom\Record
{
    protected $_sour = null;

    protected $_vers = null;

    protected $_name = null;

    protected $_corp = null;

    protected $_data = null;

    public function setCorp(Sour\Corp $corp)
    {
        $this->_corp = $corp;
    }

    /**
     * @return Sour\Corp
     */
    public function getCorp()
    {
        return $this->_corp;
    }

    public function setData(Sour\Data $data)
    {
        $this->_data = $data;
    }

    /**
     * @return Sour\Data
     */
    public function getData()
    {
        return $this->_data;
    }
}
