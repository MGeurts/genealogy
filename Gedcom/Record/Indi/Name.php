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

namespace Gedcom\Record\Indi;

/**
 * @method string getName()
 * @method string getNpfx()
 * @method string getGivn()
 * @method string getNick()
 * @method string getSpfx()
 * @method string getSurn()
 * @method string getNsfx()
 */
class Name extends \Gedcom\Record implements \Gedcom\Record\Sourceable
{
    protected $_name;

    protected $_npfx;

    protected $_givn;

    protected $_nick;

    protected $_spfx;

    protected $_surn;

    protected $_nsfx;

    protected $_fone; // Gedcom/

    protected $_romn;

    protected $_type;

    protected $_note = [];

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
