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
 * Class Addr
 * @package PhpGedcom\Record
 */
class Addr extends Record
{
    /**
     * @var string
     */
    protected $addr;

    /**
     * @var string
     */
    protected $adr1;

    /**
     * @var string
     */
    protected $adr2;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $stae;

    /**
     * @var string
     */
    protected $post;

    /**
     * @var string
     */
    protected $ctry;

    /**
     * @param string $addr
     * @return Addr
     */
    public function setAddr($addr)
    {
        $this->addr = $addr;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * @param string $adr1
     * @return Addr
     */
    public function setAdr1($adr1)
    {
        $this->adr1 = $adr1;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdr1()
    {
        return $this->adr1;
    }

    /**
     * @param string $adr2
     * @return Addr
     */
    public function setAdr2($adr2)
    {
        $this->adr2 = $adr2;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdr2()
    {
        return $this->adr2;
    }

    /**
     * @param string $city
     * @return Addr
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $stae
     * @return Addr
     */
    public function setStae($stae)
    {
        $this->stae = $stae;
        return $this;
    }

    /**
     * @return string
     */
    public function getStae()
    {
        return $this->stae;
    }

    /**
     * @param string $post
     * @return Addr
     */
    public function setPost($post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param string $ctry
     * @return Addr
     */
    public function setCtry($ctry)
    {
        $this->ctry = $ctry;
        return $this;
    }

    /**
     * @return string
     */
    public function getCtry()
    {
        return $this->ctry;
    }
}
