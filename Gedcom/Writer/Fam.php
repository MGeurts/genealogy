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

namespace Gedcom\Writer;

class Fam
{
    /**
     * @param \Gedcom\Record\Fam $sour
     * @param int                $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Fam &$fam, $level = 0)
    {
        $output = '';
        $id = $fam->getId();
        if (empty($id)) {
            return $output;
        } else {
            $output .= $level.' @'.$id.'@ FAM '."\n";
        }
        // level up
        $level++;

        // HUSB
        $husb = $fam->getHusb();
        if (!empty($husb)) {
            $output .= $level.' HUSB @'.$husb."@\n";
        }

        // WIFE
        $wife = $fam->getWife();
        if (!empty($wife)) {
            $output .= $level.' WIFE @'.$wife."@\n";
        }

        // CHIL
        $chil = $fam->getChil();
        if (!empty($chil) && (is_countable($chil) ? count($chil) : 0) > 0) {
            foreach ($chil as $item) {
                if ($item) {
                    $_convert = $level.' CHIL @'.$item."@\n";
                    $output .= $_convert;
                }
            }
        }
        // NCHI
        $nchi = $fam->getNchi();
        if (!empty($nchi)) {
            $output .= $level.' NCHI '.$nchi."\n";
        }

        // SUBM array
        $subm = $fam->getSubm();

        if (!empty($subm) && (is_countable($subm) ? count($subm) : 0) > 0) {
            foreach ($subm as $item) {
                if ($item) {
                    $output .= $level.' SUBM '.$item."\n";
                }
            }
        }

        // RIN
        $rin = $fam->getRin();
        if (!empty($rin)) {
            $output .= $level.' RIN '.$rin."\n";
        }
        // CHAN
        $chan = $fam->getChan();
        if (!empty($chan)) {
            $_convert = \Gedcom\Writer\Chan::convert($chan, $level);
            $output .= $_convert;
        }
        // SLGS
        $slgs = $fam->getSlgs();
        if (!empty($slgs) && (is_countable($slgs) ? count($slgs) : 0) > 0 && $slgs) {
            $_convert = \Gedcom\Writer\Fam\Slgs::convert($item, $level);
            $output .= $_convert;
        }

        // REFN array
        $refn = $fam->getRefn();
        if (!empty($refn) && (is_countable($refn) ? count($refn) : 0) > 0) {
            foreach ($refn as $item) {
                if ($item) {
                    $_convert = \Gedcom\Writer\Refn::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        // NOTE array
        $note = $fam->getNote();
        if (!empty($note) && (is_countable($note) ? count($note) : 0) > 0) {
            foreach ($note as $item) {
                if ($item) {
                    $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        // SOUR
        $sour = $fam->getSour();
        if (!empty($sour) && (is_countable($sour) ? count($sour) : 0) > 0) {
            foreach ($sour as $item) {
                if ($item) {
                    $_convert = \Gedcom\Writer\SourRef::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        // OBJE
        $obje = $fam->getObje();
        if (!empty($obje) && (is_countable($obje) ? count($obje) : 0) > 0) {
            foreach ($obje as $item) {
                if ($item) {
                    $_convert = \Gedcom\Writer\ObjeRef::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        // EVEN
        $even = $fam->getAllEven();
        if (!empty($even) && $even !== []) {
            foreach ($even as $eventType => $items) {
                foreach ($items as $item) {
                    if ($item) {
                        $_convert = \Gedcom\Writer\Fam\Even::convert($item, $eventType, $level);
                        $output .= $_convert;
                    }
                }
            }
        }

        // Custom tags
        $extensionTags = $fam->getExtensionTags();
        if (!empty($extensionTags) && (is_countable($extensionTags) ? count($extensionTags) : 0) > 0) {
            foreach ($extensionTags as $tag => $value) {
                if ($value) {
                    $output .= $level . ' ' . $tag . ' ' . $value . "\n";
                }
            }
        }

        return $output;
    }
}
