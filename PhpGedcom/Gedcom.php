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

namespace PhpGedcom;

/**
 * Class Gedcom
 * @package PhpGedcom
 */
class Gedcom
{
    /**
     * Stores the header information of the GEDCOM file.
     *
     * @var \PhpGedcom\Record\Head
     */
    protected $head;

    /**
     * Stores the submission information for the GEDCOM file.
     *
     * @var \PhpGedcom\Record\Subn
     */
    protected $subn;

    /**
     * Stores sources cited throughout the GEDCOM file.
     *
     * @var array
     */
    protected $sour = array();

    /**
     * Stores all the individuals contained within the GEDCOM file.
     *
     * @var array
     */
    protected $indi = array();

    /**
     * Stores all the families contained within the GEDCOM file.
     *
     * @var array
     */
    protected $fam  = array();

    /**
     * Stores all the notes contained within the GEDCOM file that are not inline.
     *
     * @var array
     */
    protected $note = array();

    /**
     * Stores all repositories that are contained within the GEDCOM file and referenced by sources.
     *
     * @var array
     */
    protected $repo = array();

    /**
     * Stores all the media objects that are contained within the GEDCOM file.
     *
     * @var array
     */
    protected $obje = array();

    /**
     * Stores information about all the submitters to the GEDCOM file.
     *
     * @var array
     */
    protected $subm = array();

    /**
     * Retrieves the header record of the GEDCOM file.
     *
     * @param Record\Head $head
     */
    public function setHead(Record\Head $head)
    {
        $this->head = $head;
    }

    /**
     * Retrieves the submission record of the GEDCOM file.
     *
     * @param Record\Subn $subn
     */
    public function setSubn(Record\Subn $subn)
    {
        $this->subn = $subn;
    }

    /**
     * Adds a source to the collection of sources.
     *
     * @param Record\Sour $sour
     */
    public function addSour(Record\Sour $sour)
    {
        $this->sour[$sour->getSour()] = $sour;
    }

    /**
     * Adds an individual to the collection of individuals.
     *
     * @param Record\Indi $indi
     */
    public function addIndi(Record\Indi $indi)
    {
        $this->indi[$indi->getId()] = $indi;
    }

    /**
     * Adds a family to the collection of families.
     *
     * @param Record\Fam $fam
     */
    public function addFam(Record\Fam $fam)
    {
        $this->fam[$fam->getId()] = $fam;
    }

    /**
     * Adds a note to the collection of notes.
     *
     * @param Record\Note $note
     */
    public function addNote(Record\Note $note)
    {
        $this->note[$note->getId()] = $note;
    }

    /**
     * Adds a repository to the collection of repositories.
     *
     * @param Record\Repo $repo
     */
    public function addRepo(Record\Repo $repo)
    {
        $this->repo[$repo->getRepo()] = $repo;
    }

    /**
     * Adds an object to the collection of objects.
     *
     * @param Record\Obje $obje
     */
    public function addObje(Record\Obje $obje)
    {
        $this->obje[$obje->getId()] = $obje;
    }

    /**
     * Adds a submitter record to the collection of submitters.
     *
     * @param Record\Subm $subm
     */
    public function addSubm(Record\Subm $subm)
    {
        $this->subm[$subm->getSubm()] = $subm;
    }

    /**
     * Gets the header information of the GEDCOM file.
     *
     * @return Record\Head
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Gets the submission record of the GEDCOM file.
     *
     * @return Record\Subn
     */
    public function getSubn()
    {
        return $this->subn;
    }

    /**
     * Gets the collection of submitters to the GEDCOM file.
     *
     * @return array
     */
    public function getSubm()
    {
        return $this->subm;
    }

    /**
     * Gets the collection of individuals stored in the GEDCOM file.
     *
     * @return array
     */
    public function getIndi()
    {
        return $this->indi;
    }

    /**
     * Gets the collection of families stored in the GEDCOM file.
     *
     * @return array
     */
    public function getFam()
    {
        return $this->fam;
    }

    /**
     * Gets the collection of repositories stored in the GEDCOM file.
     *
     * @return array
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * Gets the collection of sources stored in the GEDCOM file.
     *
     * @return array
     */
    public function getSour()
    {
        return $this->sour;
    }

    /**
     * Gets the collection of note stored in the GEDCOM file.
     *
     * @return array
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Gets the collection of objects stored in the GEDCOM file.
     *
     * @return array
     */
    public function getObje()
    {
        return $this->obje;
    }
}
