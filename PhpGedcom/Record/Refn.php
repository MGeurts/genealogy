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

use PhpGedcom\Record;

/**
 * Class Refn
 */
class Refn extends Record
{
    /**
     * @var string
     */
    protected $refn;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param  string  $refn
     * @return Refn
     */
    public function setRefn($refn)
    {
        $this->refn = $refn;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefn()
    {
        return $this->refn;
    }

    /**
     * @param  string  $type
     * @return Refn
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
