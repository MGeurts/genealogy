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
 * Class Repo
 */
class Repo extends Record implements Noteable
{
    /**
     * @var string
     */
    protected $repo;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Addr
     */
    protected $addr;

    /**
     * @var string
     */
    protected $rin;

    /**
     * @var Chan
     */
    protected $chan;

    /**
     * @var array
     */
    protected $phon = [];

    /**
     * @var array
     */
    protected $refn = [];

    /**
     * @var array
     */
    protected $note = [];

    /**
     * @return Repo
     */
    public function addPhon(Phon $phon)
    {
        $this->phon[] = $phon;

        return $this;
    }

    /**
     * @return array
     */
    public function getPhon()
    {
        return $this->phon;
    }

    /**
     * @return Repo
     */
    public function addRefn(Refn $refn)
    {
        $this->refn[] = $refn;

        return $this;
    }

    /**
     * @return array
     */
    public function getRefn()
    {
        return $this->refn;
    }

    /**
     * @return Repo
     */
    public function addNote(NoteRef $note)
    {
        $this->note[] = $note;

        return $this;
    }

    /**
     * @return array
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param  string  $repo
     * @return Repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param  string  $name
     * @return Repo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  Addr  $addr
     * @return Repo
     */
    public function setAddr($addr)
    {
        $this->addr = $addr;

        return $this;
    }

    /**
     * @return Addr
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * @param  string  $rin
     * @return Repo
     */
    public function setRin($rin)
    {
        $this->rin = $rin;

        return $this;
    }

    /**
     * @return string
     */
    public function getRin()
    {
        return $this->rin;
    }

    /**
     * @param  Chan  $chan
     * @return Repo
     */
    public function setChan($chan)
    {
        $this->chan = $chan;

        return $this;
    }

    /**
     * @return Chan
     */
    public function getChan()
    {
        return $this->chan;
    }
}
