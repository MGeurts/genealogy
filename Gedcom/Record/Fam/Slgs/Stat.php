<?php

declare(strict_types=1);

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Xiang Ming wenqiangliu344@gmail.com
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom\Record\Fam\Slgs;

class Stat extends \Gedcom\Record
{
    /**
     * string lds_spouse_sealing_date_status
     * 2020/06/27 blue.
     */
    protected $_stat;

    /**
     * string change_date.
     */
    protected $_date;
}
