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
 * Class Indi
 */
class Indi extends Record implements Noteable, Objectable, Sourceable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $chan;

    /**
     * @var array
     */
    protected $attr = [];

    /**
     * @var array
     */
    protected $even = [];

    /**
     * @var array
     */
    protected $note = [];

    /**
     * @var array
     */
    protected $obje = [];

    /**
     * @var array
     */
    protected $sour = [];

    /**
     * @var array
     */
    protected $name = [];

    /**
     * @var array
     */
    protected $alia = [];

    protected $sex;

    protected $rin;

    protected $resn;

    protected $rfn;

    protected $afn;

    /**
     * @var array
     */
    protected $fams = [];

    /**
     * @var array
     */
    protected $famc = [];

    /**
     * @var array
     */
    protected $asso = [];

    /**
     * @var array
     */
    protected $subm = [];

    /**
     * @var array
     */
    protected $anci = [];

    /**
     * @var array
     */
    protected $desi = [];

    /**
     * @var array
     */
    protected $refn = [];

    /**
     * @var Indi\Bapl
     */
    protected $bapl;

    /**
     * @var Indi\Conl
     */
    protected $conl;

    /**
     * @var Indi\Endl
     */
    protected $endl;

    /**
     * @var Indi\Slgc
     */
    protected $slgc;

    /**
     * @param  string  $id
     * @return Indi
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Indi
     */
    public function addName(Indi\Name $name)
    {
        $this->name[] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Indi
     */
    public function addAttr(Indi\Attr $attr)
    {
        $this->attr[] = $attr;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * @return Indi
     */
    public function addEven(Indi\Even $even)
    {
        $this->even[] = $even;

        return $this;
    }

    /**
     * @return array
     */
    public function getEven()
    {
        return $this->even;
    }

    /**
     * @return Indi
     */
    public function addAsso(Indi\Asso $asso)
    {
        $this->asso[] = $asso;

        return $this;
    }

    /**
     * @return array
     */
    public function getAsso()
    {
        return $this->asso;
    }

    /**
     * @return Indi
     */
    public function addRefn(Refn $ref)
    {
        $this->refn[] = $ref;

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
     * @return Indi
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
     * @return Indi
     */
    public function addObje(ObjeRef $obje)
    {
        $this->obje[] = $obje;

        return $this;
    }

    /**
     * @return array
     */
    public function getObje()
    {
        return $this->obje;
    }

    /**
     * @return Indi
     */
    public function addSour(SourRef $sour)
    {
        $this->sour[] = $sour;

        return $this;
    }

    /**
     * @return array
     */
    public function getSour()
    {
        return $this->sour;
    }

    /**
     * @param  string  $indi
     * @return Indi
     */
    public function addAlia($indi)
    {
        $this->alia[] = $indi;

        return $this;
    }

    /**
     * @return array
     */
    public function getAlia()
    {
        return $this->alia;
    }

    /**
     * @return Indi
     */
    public function addFamc(Indi\Famc $famc)
    {
        $this->famc[] = $famc;

        return $this;
    }

    /**
     * @return array
     */
    public function getFamc()
    {
        return $this->famc;
    }

    /**
     * @return Indi
     */
    public function addFams(Indi\Fams $fams)
    {
        $this->fams[] = $fams;

        return $this;
    }

    /**
     * @return array
     */
    public function getFams()
    {
        return $this->fams;
    }

    /**
     * @param  string  $subm
     * @return Indi
     */
    public function addAnci($subm)
    {
        $this->anci[] = $subm;

        return $this;
    }

    /**
     * @return array
     */
    public function getAnci()
    {
        return $this->anci;
    }

    /**
     * @param  string  $subm
     * @return Indi
     */
    public function addDesi($subm)
    {
        $this->desi[] = $subm;

        return $this;
    }

    /**
     * @return array
     */
    public function getDesi()
    {
        return $this->desi;
    }

    /**
     * @param  string  $subm
     * @return Indi
     */
    public function addSubm($subm)
    {
        $this->subm[] = $subm;

        return $this;
    }

    /**
     * @return array
     */
    public function getSubm()
    {
        return $this->subm;
    }

    /**
     * @param  string  $resn
     * @return Indi
     */
    public function setResn($resn)
    {
        $this->resn = $resn;

        return $this;
    }

    /**
     * @return string
     */
    public function getResn()
    {
        return $this->resn;
    }

    /**
     * @param  string  $sex
     * @return Indi
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param  string  $rfn
     * @return Indi
     */
    public function setRfn($rfn)
    {
        $this->rfn = $rfn;

        return $this;
    }

    /**
     * @return string
     */
    public function getRfn()
    {
        return $this->rfn;
    }

    /**
     * @param  string  $afn
     * @return Indi
     */
    public function setAfn($afn)
    {
        $this->afn = $afn;

        return $this;
    }

    /**
     * @return string
     */
    public function getAfn()
    {
        return $this->afn;
    }

    /**
     * @param  string  $chan
     * @return Indi
     */
    public function setChan($chan)
    {
        $this->chan = $chan;

        return $this;
    }

    /**
     * @return string
     */
    public function getChan()
    {
        return $this->chan;
    }

    /**
     * @param  string  $rin
     * @return Indi
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
     * @return Indi
     */
    public function setBapl(Indi\Bapl $bapl)
    {
        $this->bapl = $bapl;

        return $this;
    }

    /**
     * @return Indi\Bapl
     */
    public function getBapl()
    {
        return $this->bapl;
    }

    /**
     * @return Indi
     */
    public function setConl(Indi\Conl $conl)
    {
        $this->conl = $conl;

        return $this;
    }

    /**
     * @return Indi\Conl
     */
    public function getConl()
    {
        return $this->conl;
    }

    /**
     * @return Indi
     */
    public function setEndl(Indi\Endl $endl)
    {
        $this->endl = $endl;

        return $this;
    }

    /**
     * @return Indi\Endl
     */
    public function getEndl()
    {
        return $this->endl;
    }

    /**
     * @return Indi
     */
    public function setSlgc(Indi\Slgc $slgc)
    {
        $this->slgc = $slgc;

        return $this;
    }

    /**
     * @return Indi\Slgc
     */
    public function getSlgc()
    {
        return $this->slgc;
    }
}
