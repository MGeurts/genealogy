<?php

namespace Gedcom\Parser\Head;

class Dest extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        $dest = new \Gedcom\Record\Head\Dest();
        $dest->setDest(trim((string) $record[2]));

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType = strtoupper(trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                // Add cases for DEST sub-tags here if needed
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $dest;
    }
}