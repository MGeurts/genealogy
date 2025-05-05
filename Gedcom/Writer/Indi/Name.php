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

namespace Gedcom\Writer\Indi;

class Name
{
    /**
     * @param  \Gedcom\Record\Indi\Name  $attr
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Indi\Name &$name, $level = 0)
    {
        $output = '';
        // NAME
        $_name = $name->getName();
        if (empty($_name)) {
            return $output;
        }
        $output .= $level . ' NAME ' . $_name . "\n";
        // level up
        $level++;

        // NPFX
        $npfx = $name->getNpfx();
        if (! empty($npfx)) {
            $output .= $level . ' NPFX ' . $npfx . "\n";
        }

        // GIVN
        $givn = $name->getGivn();
        if (! empty($givn)) {
            $output .= $level . ' GIVN ' . $givn . "\n";
        }
        // NICK
        $nick = $name->getNick();
        if (! empty($nick)) {
            $output .= $level . ' NICK ' . $nick . "\n";
        }
        // SPFX
        $spfx = $name->getSpfx();
        if (! empty($spfx)) {
            $output .= $level . ' SPFX ' . $spfx . "\n";
        }
        // SURN
        $surn = $name->getSurn();
        if (! empty($surn)) {
            $output .= $level . ' SURN ' . $surn . "\n";
        }
        // NSFX
        $nsfx = $name->getNsfx();
        if (! empty($nsfx)) {
            $output .= $level . ' NSFX ' . $nsfx . "\n";
        }
        // SOUR
        $sour = $name->getSour();
        if (! empty($sour) && (is_countable($sour) ? count($sour) : 0) > 0) {
            foreach ($sour as $item) {
                $_convert = \Gedcom\Writer\SourRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // note
        $note = $name->getSour();
        if (! empty($note) && (is_countable($note) ? count($note) : 0) > 0) {
            foreach ($note as $item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // TYPE
        $type = $name->getType();
        if (! empty($type)) {
            $output .= $level . ' TYPE ' . $type . "\n";
        }

        return $output;
    }
}
