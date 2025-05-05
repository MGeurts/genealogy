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

/**
 * Class Caln.
 */
class Caln extends \Gedcom\Record
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
     * @param  string  $caln
     * @return Caln
     */
    public function setCaln($caln = '')
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
     * @param  string  $medi
     * @return Caln
     */
    public function setMedi($medi = '')
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
