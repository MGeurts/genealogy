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

class NoteRef extends \PhpGedcom\Record implements Sourceable
{
    protected $_isRef = false;

    protected $_note = '';

    protected $_sour = [];

    public function setIsReference($isReference = true)
    {
        $this->_isRef = $isReference;
    }

    public function getIsReference()
    {
        return $this->_isRef;
    }

    public function addSour(SourRef $sour)
    {
        $this->_sour[] = $sour;
    }
}
