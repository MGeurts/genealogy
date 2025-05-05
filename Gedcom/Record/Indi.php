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
 * Class Indi.
 */
class Indi extends \Gedcom\Record implements Noteable, Objectable, Sourceable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $gid;

    /**
     * @var string
     */
    protected $uid;

    /**
     * @var string
     */
    protected $resn;

    /**
     * @var Indi\Name[]
     */
    protected $name = [];

    /**
     * @var string
     */
    protected $sex;

    /**
     * @var Indi\Even[]
     */
    protected $even = [];

    /**
     * @var Indi\Attr[]
     */
    protected $attr = [];

    /**
     * @var Indi\Bapl
     */
    protected $bapl = [];

    /**
     * @var Indi\Conl
     */
    protected $conl = [];

    /**
     * @var Indi\Endl
     */
    protected $endl = [];

    /**
     * @var Indi\Slgc
     */
    protected $slgc = [];

    /**
     * @var Indi\Famc[]
     */
    protected $famc = [];

    /**
     * @var Indi\Fams[]
     */
    protected $fams = [];

    /**
     * @var string[]
     */
    protected $subm = [];

    /**
     * @var string[]
     */
    protected $alia = [];

    /**
     * @var string[]
     */
    protected $anci = [];

    /**
     * @var string[]
     */
    protected $desi = [];

    /**
     * @var string
     */
    protected $rfn;

    /**
     * @var string
     */
    protected $afn;

    /**
     * @var Refn[]
     */
    protected $refn = [];

    /**
     * @var string
     */
    protected $rin;

    /**
     * @var string
     */
    protected $chan;

    /**
     * @var Indi\Note[]
     */
    protected $note = [];

    /**
     * @var Obje[]
     */
    protected $obje = [];

    /**
     * @var Sour[]
     */
    protected $sour = [];

    /**
     * @var Indi\Asso[]
     */
    protected $asso = [];

    protected $deathday = [];

    protected $birt;

    protected $buri;

    protected $deat;

    protected $chr;

    public function setBirt($birt)
    {
        $this->birt = $birt;
    }

    public function getBirt()
    {
        return $this->birt;
    }

    public function setBuri($buri)
    {
        $this->buri = $buri;
    }

    public function getBuri()
    {
        return $this->buri;
    }

    public function setDeat($deat)
    {
        $this->deat = $deat;
    }

    public function getDeat()
    {
        return $this->deat;
    }

    public function setChr($chr)
    {
        $this->chr = $chr;
    }

    public function getChr()
    {
        return $this->chr;
    }

    /**
     * @param  string  $id
     * @return Indi
     */
    public function setId($id = '')
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
     * @param  string  $id
     * @return Indi
     */
    public function setGid($gid = '')
    {
        $this->gid = $gid;

        return $this;
    }

    /**
     * @return string
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * @param  string  $uid
     * @return Indi
     */
    public function setUid($uid = '')
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param  Indi\Name  $name
     * @return Indi
     */
    public function addName($name = [])
    {
        $this->name[] = $name;

        return $this;
    }

    /**
     * @return Indi\Name[]
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  Indi\Attr  $attr
     * @return Indi
     */
    public function addAttr($attr = [])
    {
        $attrName = $attr->getType();

        if (! array_key_exists($attrName, $this->attr)) {
            $this->attr[$attrName] = [];
        }

        $this->attr[$attrName][] = $attr;

        return $this;
    }

    /**
     * @return Indi\Attr[]
     */
    public function getAllAttr()
    {
        return $this->attr;
    }

    /**
     * @return Indi\Attr[]
     */
    public function getAttr($key = '')
    {
        if (isset($this->attr[mb_strtoupper((string) $key)])) {
            return $this->attr[mb_strtoupper((string) $key)];
        }
    }

    /**
     * @param  Indi\Even  $even
     * @return Indi
     */
    public function addEven($even = [])
    {
        $evenName = $even->getType();

        if (! array_key_exists($evenName, $this->even)) {
            $this->even[$evenName] = [];
        }

        $this->even[$evenName][] = $even;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllEven()
    {
        return $this->even;
    }

    /**
     * @return array
     */
    public function getEven($key = '')
    {
        if (isset($this->even[mb_strtoupper((string) $key)])) {
            return $this->even[mb_strtoupper((string) $key)];
        }

        return [];
    }

    /**
     * @param  Indi\Asso  $asso
     * @return Indi
     */
    public function addAsso($asso = [])
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
     * @param  Refn  $ref
     * @return Indi
     */
    public function addRefn($ref = [])
    {
        $this->refn[] = $ref;

        return $this;
    }

    /**
     * @return Refn[]
     */
    public function getRefn()
    {
        return $this->refn;
    }

    /**
     * @param  NoteRef  $note
     * @return Indi
     */
    public function addNote($note = [])
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
     * @param  ObjeRef  $obje
     * @return Indi
     */
    public function addObje($obje = [])
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
     * @param  SourRef  $sour
     * @return Indi
     */
    public function addSour($sour = [])
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
    public function addAlia($indi = '')
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
     * @param  Indi\Famc  $famc
     * @return Indi
     */
    public function addFamc($famc = [])
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
     * @param  Indi\Fams  $fams
     * @return Indi
     */
    public function addFams($fams = [])
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
    public function addAnci($subm = '')
    {
        $this->anci[] = $subm;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAnci()
    {
        return $this->anci;
    }

    /**
     * @param  string  $subm
     * @return Indi
     */
    public function addDesi($subm = '')
    {
        $this->desi[] = $subm;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getDesi()
    {
        return $this->desi;
    }

    /**
     * @param  string  $subm
     * @return Indi
     */
    public function addSubm($subm = '')
    {
        $this->subm[] = $subm;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getSubm()
    {
        return $this->subm;
    }

    /**
     * @param  string  $resn
     * @return Indi
     */
    public function setResn($resn = '')
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
    public function setSex($sex = '')
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
    public function setRfn($rfn = '')
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
    public function setAfn($afn = '')
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
    public function setChan($chan = null)
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
    public function setRin($rin = '')
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
     * @param  Indi\Bapl  $bapl
     * @return Indi
     */
    public function setBapl($bapl = [])
    {
        $this->bapl = $bapl;

        return $this;
    }

    /**
     * @param  Indi\Bapl  $bapl
     * @return Indi
     */
    public function addBapl($bapl = null)
    {
        $this->bapl[] = $bapl;

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
     * @param  Indi\Conl  $conl
     * @return Indi
     */
    public function setConl($conl = [])
    {
        $this->conl = $conl;

        return $this;
    }

    /**
     * @param  Indi\Conl  $conl
     * @return Indi
     */
    public function addConl($conl)
    {
        $this->conl[] = $conl;

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
     * @param  Indi\Endl  $endl
     * @return Indi
     */
    public function setEndl($endl = [])
    {
        $this->endl = $endl;

        return $this;
    }

    /**
     * @param  Indi\Endl  $endl
     * @return Indi
     */
    public function addEndl($endl)
    {
        $this->endl[] = $endl;

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
     * @param  Indi\Slgc  $slgc
     * @return Indi
     */
    public function setSlgc($slgc = [])
    {
        $this->slgc = $slgc;

        return $this;
    }

    /**
     * @param  Indi\Slgc  $slgc
     * @return Indi
     */
    public function addSlgc($slgc)
    {
        $this->slgc[] = $slgc;

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
