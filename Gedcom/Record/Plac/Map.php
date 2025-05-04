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

namespace Gedcom\Record\Plac;

/**
 * Class Refn.
 */
class Map extends \Gedcom\Record
{
    /**
     * @var string place_latitude
     */
    protected $lati;

    /**
     * @var string place_longitude
     */
    protected $long;

    /**
     * @param string $lati
     *
     * @return Map
     */
    public function setLati($lati = '')
    {
        $this->lati = $lati;

        return $this;
    }

    /**
     * @param string $long
     *
     * @return Map
     */
    public function setLong($long = '')
    {
        $this->long = $long;

        return $this;
    }
}
