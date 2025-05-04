<?php

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

namespace Gedcom\Record\ObjeRef\File;

/**
 * Class Refn.
 */
class Form extends \Gedcom\Record
{
    /**
     * @var string multimedia_format
     */
    protected $form;

    /**
     * @var string source_media_type
     *             for only obje
     */
    protected $type;

    /**
     * @var string source_media_type
     *             for only objeref
     */
    protected $medi;
}
