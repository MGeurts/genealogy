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
 * Class Phon
 */
class Phon extends Record
{
    /**
     * @var string
     */
    protected $phon = null;

    /**
     * @return Phon
     */
    public function setPhon($phon)
    {
        $this->phon = $phon;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPhon()
    {
        return $this->phon;
    }
}
