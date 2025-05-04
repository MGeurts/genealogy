<?php

namespace Gedcom\Writer\Head;

class Dest
{
    public static function convert(\Gedcom\Record\Head\Dest $dest, $level)
    {
        $output = '';
        $_dest = $dest->getDest();
        if ($_dest) {
            $output .= $level.' DEST '.$_dest."\n";
        }

        // Add conversion for sub-tags if needed

        return $output;
    }
}