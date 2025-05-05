<?php

declare(strict_types=1);

namespace Gedcom\Writer;

class RepoRef
{
    public static function convert(\Gedcom\Record\RepoRef $reporef, int $level): string
    {
        $output = '';
        $_repo  = $reporef->getRepo();

        if (empty($_repo)) {
            return $output;
        }

        $output .= $level . ' REPO ' . $_repo . "\n";

        // level up
        $level++;

        // Note array
        $notes = $reporef->getNote();
        if (! empty($notes) && count($notes) > 0) {
            foreach ($notes as $item) {
                $output .= NoteRef::convert($item, $level);
            }
        }

        // _caln array
        $calns = $reporef->getCaln();
        if (! empty($calns) && count($calns) > 0) {
            foreach ($calns as $item) {
                $output .= Caln::convert($item, $level);
            }
        }

        return $output;
    }
}
