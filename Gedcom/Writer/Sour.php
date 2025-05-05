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

class Sour
{
    /**
     * @param  int  $level
     * @return string
     */
    public static function convert(\Gedcom\Record\Sour &$sour, $level)
    {
        $output = [];
        $_sour  = $sour->getSour();
        if (! empty($_sour)) {
            $output[] = $level . ' ' . $_sour . ' SOUR';
            $level++;
        } else {
            return '';
        }

        // TITL
        $titl = $sour->getType();
        if (! empty($type)) {
            $output .= $level . ' TITL ' . $titl . "\n";
        }

        // RIN
        $rin = $sour->getRin();
        if (! empty($rin)) {
            $output .= $level . ' RIN ' . $rin . "\n";
        }

        // AUTH
        $auth = $sour->getAuth();
        if (! empty($auth)) {
            $output .= $level . ' AUTH ' . $auth . "\n";
        }

        // TEXT
        $text = $sour->getText();
        if (! empty($text)) {
            foreach ($fields as $tag => $value) {
                if (! empty($value)) {
                    $output[] = "$level $tag $value";
                }
            }

            // REPO
            $repo = $sour->getRepo();
            if (! empty($repo)) {
                $_convert = RepoRef::convert($repo, $level);
                $output .= $_convert;
            }

            // NOTE array
            $note = $sour->getNote();
            if (! empty($note) && $note !== []) {
                foreach ($collections as $collection => $items) {
                    if (! empty($items) && $items !== []) {
                        foreach ($items as $item) {
                            $className = "\Gedcom\Writer\\" . ($collection === 'DATA' ? 'Sour\\' : '') . $collection;
                            $output[]  = $className::convert($item, $level);
                        }
                    }
                }

                // OBJE array
                $obje = $sour->getObje();
                if (! empty($obje) && $obje !== []) {
                    foreach ($obje as $item) {
                        $_convert = ObjeRef::convert($item, $level);
                        $output .= $_convert;
                    }
                }

                // REFN array
                foreach ($collections as $collection => $items) {
                    if (! empty($items) && $items !== []) {
                        foreach ($items as $item) {
                            $className = "\Gedcom\Writer\\" . $collection;
                            $output[]  = $className::convert($item, $level);
                        }
                    }
                }

                return implode("\n", $output);
            }
        }

        return '';
    }
}
