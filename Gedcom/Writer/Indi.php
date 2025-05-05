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

class Indi
{
    /**
     * @param  string  $format
     * @return string
     */
    public static function convert(\Gedcom\Record\Indi &$indi)
    {
        $level = 0;

        $indi->getId();

        // gid
        $gid    = $indi->getGid();
        $output = $level . ' @' . $gid . "@ INDI\n";

        // increase level after start indi
        $level++;

        // uid
        $uid = $indi->getUid();
        if (! empty($uid)) {
            $output .= $level . ' _UID ' . $uid . "\n";
        }

        // $attr
        // Gedcom/Record/Attr extend Gedcom/Record/Even and there is no change.
        // So used convert Even
        $attr = $indi->getAllAttr();
        if (! empty($attr) && $attr !== []) {
            foreach ($attr as $item) {
                $_convert = Indi\Even::convert($item, $level);
                $output .= $_convert;
            }
        }

        // $even
        $even = $indi->getAllEven();
        if (! empty($even) && $even !== []) {
            foreach ($even as $items) {
                foreach ($items as $item) {
                    if ($item) {
                        $_convert = Indi\Even::convert($item, $level);
                        $output .= $_convert;
                    }
                }
            }
        }

        // $note

        $note = $indi->getNote();
        if (! empty($note) && $note !== []) {
            foreach ($note as $item) {
                $_convert = NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // $obje
        $obje = $indi->getObje();
        if (! empty($obje) && $obje !== []) {
            foreach ($obje as $item) {
                $_convert = ObjeRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // $sour
        $sour = $indi->getSour();
        if (! empty($sour) && $sour !== []) {
            foreach ($sour as $item) {
                $_convert = SourRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // $name
        $name = $indi->getName();
        if (! empty($name) && $name !== []) {
            foreach ($name as $item) {
                $_convert = Indi\Name::convert($item, $level);
                $output .= $_convert;
            }
        }

        // $alia
        $alia = $indi->getAlia();
        if (! empty($alia) && $alia !== []) {
            foreach ($alia as $item) {
                if (! empty($item)) {
                    $_convert = $level . ' ALIA ' . $item . "\n";
                    $output .= $_convert;
                }
            }
        }

        // $sex
        $sex = $indi->getSex();
        if (! empty($sex)) {
            $output .= $level . ' SEX ' . $sex . "\n";
        }

        // $birthday
        $birthday = $indi->getBirt();
        if (! empty($birthday)) {
            $output .= $level . ' BIRT ' . "\n";
            $output .= ($level + 1) . ' DATE ' . $birthday . "\n";
        }

        // $deathday
        $deathday = $indi->getDeat();
        if (! empty($deathday)) {
            $output .= $level . ' DEAT ' . "\n";
            $output .= ($level + 1) . ' DATE ' . $deathday . "\n";
        }

        // $burialday
        $burialday = $indi->getBuri();
        if (! empty($burialday)) {
            $output .= $level . ' BURI ' . "\n";
            $output .= ($level + 1) . ' DATE ' . $burialday . "\n";
        }

        // $rin
        $rin = $indi->getRin();
        if (! empty($rin)) {
            $output .= $level . ' RIN ' . $rin . "\n";
        }

        // $resn
        $resn = $indi->getResn();
        if (! empty($resn)) {
            $output .= $level . ' RESN ' . $resn . "\n";
        }

        // $rfn
        $rfn = $indi->getRfn();
        if (! empty($rfn)) {
            $output .= $level . ' RFN ' . $rfn . "\n";
        }

        // $afn
        $afn = $indi->getAfn();
        if (! empty($afn)) {
            $output .= $level . ' AFN ' . $afn . "\n";
        }

        // Fams[]
        $fams = $indi->getFams();
        if (! empty($fams) && $fams !== []) {
            foreach ($fams as $item) {
                $_convert = Indi\Fams::convert($item, $level);
                $output .= $_convert;
            }
        }

        // Famc[]
        $famc = $indi->getFamc();
        if (! empty($famc) && $famc !== []) {
            foreach ($famc as $item) {
                $_convert = Indi\Famc::convert($item, $level);
                $output .= $_convert;
            }
        }

        // Asso[]
        $asso = $indi->getAsso();
        if (! empty($asso) && $asso !== []) {
            foreach ($asso as $item) {
                $_convert = Indi\Asso::convert($item, $level);
                $output .= $_convert;
            }
        }

        // $subm
        $subm = $indi->getSubm();
        if (! empty($subm) && $subm !== []) {
            foreach ($subm as $item) {
                if (! empty($item)) {
                    $_convert = $level . ' SUBM ' . $item . "\n";
                    $output .= $_convert;
                }
            }
        }

        // $anci
        $anci = $indi->getAnci();
        if (! empty($anci) && $anci !== []) {
            foreach ($anci as $item) {
                $_convert = $level . ' ANCI ' . $item . "\n";
                $output .= $_convert;
            }
        }

        // $desi
        $desi = $indi->getDesi();
        if (! empty($desi) && $desi !== []) {
            foreach ($desi as $item) {
                $_convert = $level . ' DESI ' . $item . "\n";
                $output .= $_convert;
            }
        }

        // Refn[]
        $refn = $indi->getRefn();
        if (! empty($refn) && $refn !== []) {
            foreach ($refn as $item) {
                $_convert = Refn::convert($item, $level);
                $output .= $_convert;
            }
        }

        // chan
        $chan = $indi->getChan();
        if (! empty($chan)) {
            $output .= $level . ' CHAN ' . "\n";
            $output .= ($level + 1) . ' DATE ' . $chan[0] . "\n";
            $output .= ($level + 1) . ' TIME ' . $chan[1] . "\n";
        }

        // Bapl
        // Currently Bapl is empty
        // $bapl = $indi->getBapl();
        // if(!empty($bapl)){
        //     $_convert = \Gedcom\Writer\Indi\Bapl::convert($bapl, $level);
        //     $output.=$_convert;
        // }

        // Conl
        // Currently Conl is empty
        // $conl = $indi->getConl();
        // if(!empty($conl)){
        //     $_convert = \Gedcom\Writer\Indi\Conl::convert($conl, $level);
        //     $output.=$_convert;
        // }

        // Endl
        // Currently Endl is empty
        // $endl = $indi->getEndl();
        // if(!empty($endl)){
        //     $_convert = \Gedcom\Writer\Indi\Endl::convert($endl, $level);
        //     $output.=$_convert;
        // }

        // Slgc
        // Currently Endl is empty
        // $slgc = $indi->getSlgc();
        // if(!empty($slgc)){
        //     $_convert = \Gedcom\Writer\Indi\Slgc::convert($slgc, $level);
        //     $output.=$_convert;
        // }

        return $output;
    }
}
