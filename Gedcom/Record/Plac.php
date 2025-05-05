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

class Plac extends \Gedcom\Record implements Noteable
{
    /**
     * string plac.
     */
    protected $_plac;

    /**
     * string place_hierarchy.
     */
    protected $_form;

    /**
     * array PhpRecord\Plac\Fone.
     */
    protected $_fone;

    /**
     * array PhpRecord\Plac\Romn.
     */
    protected $_romn;

    /**
     * PhpRecord\Plac\Map.
     */
    protected $_map;

    /**
     * array PhpRecord\NoteRef.
     */
    protected $_note;

    /**
     * @param  PhpRecord\NoteRef  $note
     * @return Plac
     */
    public function addNote($note = null)
    {
        $this->_note[] = $note;

        return $this;
    }
}
