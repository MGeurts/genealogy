<?php

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Xiang Ming <wenqiangliu344@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Xiang Ming
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom\Writer\Sour;

class Data
{
    /**
     * @param int $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Sour\Data &$data, $level = 0)
    {
        $output = $level." DATA\n";
        $level++;

        // $_date;
        $date = $data->getDate();
        if (!empty($date)) {
            $output .= $level.' DATE '.$date."\n";
        }

        // $_agnc AGNC
        $_agnc = $data->getAgnc();
        if (!empty($_agnc)) {
            $output .= $level.' AGNC '.$_agnc."\n";
        }

        // $_text
        $_text = $data->getText();
        if (!empty($_text)) {
            $output .= $level.' TEXT '.$_text."\n";
        }

        // $_note
        $note = $data->getNote();
        foreach ($note as $item) {
            $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
            $output .= $_convert;
        }

        // $_even
        $_even = $data->getEven();
        foreach ($_even as $item) {
            $_convert = \Gedcom\Writer\Sour\Data\Even::convert($item, $level);
            $output .= $_convert;
        }

        return $output;
    }
}
