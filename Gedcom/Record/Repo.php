<?php

declare(strict_types=1);

namespace Gedcom\Record;

final class Repo extends \Gedcom\Record implements Noteable
{
    private string $repo = '';

    private string $name = '';

    private ?Addr $addr = null;

    private array $phon = [];

    private array $email = [];

    private array $fax = [];

    private array $www = [];

    private string $rin = '';

    private ?Chan $chan = null;

    private array $refn = [];

    private array $note = [];

    public function addPhon(string $phon): self
    {
        $this->phon[] = $phon;

        return $this;
    }

    public function getPhon(): array
    {
        return $this->phon;
    }

    /**
     * @param null
     * @return Repo
     */
    public function addEmail($email = null)
    {
        $this->email[] = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null
     * @return Repo
     */
    public function addFax($fax = null)
    {
        $this->fax[] = $fax;

        return $this;
    }

    /**
     * @return array
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param null
     * @return Repo
     */
    public function addWww($www = null)
    {
        $this->www[] = $www;

        return $this;
    }

    /**
     * @return array
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * @param  null|Refn  $refn
     * @return Repo
     */
    public function addRefn($refn = null)
    {
        if (empty($refn)) {
            $refn = new Refn();
        }
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
     * @param  null|NoteRef  $note
     * @return Repo
     */
    public function addNote($note = null)
    {
        if (empty($node)) {
            $note = new NoteRef();
        }
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
    public function setRepo($repo = '')
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
    public function setName($name = '')
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
     * @param  null|Addr  $addr
     * @return Repo
     */
    public function setAddr($addr = null)
    {
        if (empty($addr)) {
            $addr = new Addr();
        }
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
     * @param  Chan  $chan
     * @return Repo
     */
    public function setChan($chan = [])
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
