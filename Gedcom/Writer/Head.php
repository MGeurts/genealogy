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

namespace Gedcom\Writer;

class Head
{
    /**
     * @param string $format
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Head &$head, $format = self::GEDCOM55)
    {
        $level = 0;
        $output = $level." HEAD\n";

        // level up
        $level++;

        //SOUR
        $sour = $head->getSour();
        if ($sour) {
            $_convert = \Gedcom\Writer\Head\Sour::convert($sour, $level);
            $output .= $_convert;
        }

        // DEST
        $dest = $head->getDest();
        if ($dest !== '' && $dest !== '0') {
            $output .= $level.' DEST '.$dest."\n";
        }

        //Subm
        $subm = $head->getSubm();
        if ($subm !== '' && $subm !== '0') {
            $output .= $level.' SUBM '.$subm."\n";
        }

        // SUBN
        $subn = $head->getSubn();
        if ($subn !== '' && $subn !== '0') {
            $output .= $level.' SUBN '.$subn."\n";
        }

        // FILE
        $file = $head->getFile();
        if ($file !== '' && $file !== '0') {
            $output .= $level.' FILE '.$file."\n";
        }

        // COPR
        $copr = $head->getCopr();
        if ($copr !== '' && $copr !== '0') {
            $output .= $level.' COPR '.$copr."\n";
        }

        // LANG
        $lang = $head->getLang();
        if ($lang !== '' && $lang !== '0') {
            $output .= $level.' LANG '.$lang."\n";
        }
        // DATE
        $date = $head->getDate();
        if ($date) {
            $_convert = \Gedcom\Writer\Head\Date::convert($date, $level);
            $output .= $_convert;
        }

        // GEDC
        $gedc = $head->getGedc();
        if ($gedc) {
            $_convert = \Gedcom\Writer\Head\Gedc::convert($gedc, $level);
            $output .= $_convert;
        }

        // CHAR
        $char = $head->getChar();
        if ($char) {
            $_convert = \Gedcom\Writer\Head\Char::convert($char, $level);
            $output .= $_convert;
        }
        // PLAC
        $plac = $head->getPlac();
        if ($plac) {
            $_convert = \Gedcom\Writer\Head\Plac::convert($plac, $level);
            $output .= $_convert;
        }

        // NOTE
        $note = $head->getNote();
        if ($note !== '' && $note !== '0') {
            $output .= $level.' NOTE '.$note."\n";
        }
        //
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
