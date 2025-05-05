<?php

declare(strict_types=1);

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

namespace Gedcom\Writer;

class Chan
{
    /**
     * @param  \Gedcom\Record\Chan  $note
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Chan &$chan, $level)
    {
        $output = $level . " CHAN \n";
        // level up
        $level++;
        // DATE
        $_date = $chan->getDate();
        if (! empty($_date)) {
            $output .= $level . ' DATE ' . $_date . "\n";
        }
        // TIME
        $_time = $chan->getDate();
        if (! empty($_time)) {
            $output .= $level . ' DATE ' . $_time . "\n";
        }
        // $_note = array()
        $_note = $chan->getNote();
        if (! empty($_note) && $_note !== []) {
            foreach ($_note as $item) {
                $_convert = NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
