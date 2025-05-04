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

/**
 * Class Phon.
 */
class Phon extends \Gedcom\Record
{
    /**
     * @var string
     */
    protected $phon;

    /**
     * @param $phon
     *
     * @return Phon
     */
    public function setPhon($phon = [])
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
