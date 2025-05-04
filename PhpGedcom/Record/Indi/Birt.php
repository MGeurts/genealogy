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

namespace PhpGedcom\Record\Indi;

use PhpGedcom\Record\Indi\Even;

/**
 * Class Birt
 * @package PhpGedcom\Record\Indi
 */
class Birt extends Even
{
    /**
     * @var string
     */
    protected $famc;

    /**
     * @param string $famc
     * @return Birt
     */
    public function setFamc($famc)
    {
        $this->famc = $famc;
        return $this;
    }

    /**
     * @return string
     */
    public function getFamc()
    {
        return $this->famc;
    }
}
