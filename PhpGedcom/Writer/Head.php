<?php

/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2020, Kristopher Wilson
 * @package         php-gedcom
 * @license         MIT
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Writer;

/**
 *
 */
class Head
{
    /**
     * @param \PhpGedcom\Record\Head $head
     * @param string $format
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Head &$head, $format = self::GEDCOM55)
    {
        $output = "0 HEAD\n" .

            ($head->getSour() ? Head\Sour::convert($head->getSour(), $format) : '') .
            //"1 DEST " . $head-> . "\n" .
            "1 DATE " . date("d M Y") . "\n" .
            "2 TIME " . date("H:i:s") . "\n";

        /*
            +1 SUBM @<XREF:SUBM>@  {1:1}
            +1 SUBN @<XREF:SUBN>@  {0:1}
            +1 FILE <FILE_NAME>  {0:1}
            +1 COPR <COPYRIGHT_GEDCOM_FILE>  {0:1}
            +1 GEDC        {1:1}
              +2 VERS <VERSION_NUMBER>  {1:1}
              +2 FORM <GEDCOM_FORM>  {1:1}
            +1 CHAR <CHARACTER_SET>  {1:1}
              +2 VERS <VERSION_NUMBER>  {0:1}
            +1 LANG <LANGUAGE_OF_TEXT>  {0:1}
            +1 PLAC        {0:1}
              +2 FORM <PLACE_HIERARCHY>  {1:1}
            +1 NOTE <GEDCOM_CONTENT_DESCRIPTION>  {0:1}
              +2 [CONT|CONC] <GEDCOM_CONTENT_DESCRIPTION>  {0:M}
        */

        return $output;
    }
}
