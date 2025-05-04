<?php

namespace Gedcom\Parser;

class RepoRef extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser): ?\Gedcom\Record\RepoRef
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[2])) {
            $identifier = $parser->normalizeIdentifier($record[2]);
        } else {
            $parser->skipToNextLevel($depth);
            return null;
        }

        $repo = new \Gedcom\Record\RepoRef();
        $repo->setRepo($identifier);

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType = strtoupper(trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            match ($recordType) {
                'CALN' => $repo->addCaln(\Parser\Caln::parse($parser)),
                'NOTE' => $repo->addNote(\Gedcom\Parser\NoteRef::parse($parser)),
                default => $parser->logUnhandledRecord(self::class.' @ '.__LINE__)
            };

            $parser->forward();
        }

        return $repo;
    }
}