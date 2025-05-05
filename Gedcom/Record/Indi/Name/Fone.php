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

namespace Gedcom\Record\Indi\Name;

/**
 * Class Refn.
 */
class Fone extends \Gedcom\Record
{
    /**
     * @var string phonetic_variation
     */
    protected $fone;

    /**
     * @var string phonetic_type
     */
    protected $type;

    /**
     * string name_piece_prefix.
     */
    protected $_npfx;

    /**
     * string name_piece_given.
     */
    protected $_givn;

    /**
     * string name_piece_nickname.
     */
    protected $_nick;

    /**
     * strign name_piece_surname_prefix.
     */
    protected $_spfx;

    /**
     * string name_piece_surname.
     */
    protected $_surn;

    /**
     * string name_piece_suffix.
     */
    protected $_nsfx;

    /**
     * PhpRecord\NoteRef.
     */
    protected $_note = [];

    /**
     * PhpRecord\SourRef.
     */
    protected $_sour = [];

    public function addSour($sour = [])
    {
        $this->_sour[] = $sour;
    }

    public function addNote($note = [])
    {
        $this->_note[] = $note;
    }
}
