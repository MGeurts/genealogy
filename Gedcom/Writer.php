<?php

declare(strict_types=1);

namespace Gedcom;

use Gedcom\Writer\{Fam, Head, Indi, Note, Obje, Repo, Sour, Subm, Subn};

final class Writer
{
    final public const GEDCOM55 = 'gedcom5.5';

    public static function convert(Gedcom $gedcom, string $format = self::GEDCOM55): string
    {
        $output = '';
        $formatInformation = FormatInformation::addFormatInformation($format);

        $output .= self::convertHead($gedcom->getHead(), $format, $formatInformation);
        $output .= self::convertSubn($gedcom->getSubn());
        $output .= self::convertSubms($gedcom->getSubm());
        $output .= self::convertSours($gedcom->getSour());
        $output .= self::convertIndis($gedcom->getIndi());
        $output .= self::convertFams($gedcom->getFam());
        $output .= self::convertNotes($gedcom->getNote());
        $output .= self::convertRepos($gedcom->getRepo());
        $output .= self::convertObjes($gedcom->getObje());

        return $output . "0 TRLR\n";
    }

    /**
     * @param        $gedcom The GEDCOM object
     * @param string $format The format to convert the GEDCOM object to
     *
     * @return string The contents of the document in the converted format
     */
    protected static function convertHead($head, string $format, string $formatInformation): string
    {
        $output = '';
        if ($head) {
            $output = $formatInformation . Head::convert($head, $format);
        }
        return $output;
    }

    protected static function convertSubn($subn): string
    {
        $output = '';
        if ($subn) {
            $output .= Subn::convert($subn);
        }
        return $output;
    }

    protected static function convertSubms(array $subms): string
    {
        $output = '';
        foreach ($subms as $item) {
            if ($item) {
                $output .= Subm::convert($item);
            }
        }
        return $output;
    }

    protected static function convertSours(array $sours): string
    {
        $output = '';
        foreach ($sours as $item) {
            if ($item) {
                $output .= Sour::convert($item, 0);
            }
        }
        return $output;
    }

    protected static function convertIndis(array $indis): string
    {
        $output = '';
        foreach ($indis as $indi) {
            if ($indi) {
                $output .= Indi::convert($indi);
                foreach ($indi->getEven() as $eventType => $events) {
                    foreach ($events as $event) {
                        $output .= Indi::convertEvent($event, $eventType);
                    }
                }
            }
        }
        return $output;
    }

    protected static function convertFams(array $fams): string
    {
        $output = '';
        foreach ($fams as $item) {
            if ($item) {
                $output .= Fam::convert($item);
            }
        }
        return $output;
    }


    protected static function convertNotes(array $notes): string
    {
        $output = '';
        foreach ($notes as $item) {
            if ($item) {
                $output .= Note::convert($item);
            }
        }
        return $output;
    }

    protected static function convertRepos(array $repos): string
    {
        $output = '';
        foreach ($repos as $item) {
            if ($item) {
                $output .= Repo::convert($item);
            }
        }
        return $output;
    }

    protected static function convertObjes(array $objes): string
    {
        $output = '';
        foreach ($objes as $item) {
            if ($item) {
                $output .= Obje::convert($item);
            }
        }
        return $output;
    }
}