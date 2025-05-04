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

use PhpGedcom\Record;

/**
 * Class Caln
 * @package PhpGedcom\Record
 */
class Caln extends Record
{
    /**
     * @var string
     */
    protected $caln;

    /**
     * @var string
     */
    protected $medi;

    /**
     * @param string $caln
     * @return Caln
     */
    public function setCaln($caln)
    {
        $this->caln = $caln;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaln()
    {
        return $this->caln;
    }

    /**
     * @param string $medi
     * @return Caln
     */
    public function setMedi($medi)
    {
        $this->medi = $medi;
        return $this;
    }

    /**
     * @return string
     */
    public function getMedi()
    {
        return $this->medi;
    }
}
