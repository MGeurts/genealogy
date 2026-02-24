<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

use Illuminate\Support\Facades\Log;

/**
 * GEDCOM file parser - handles parsing raw GEDCOM content into structured data
 */
class GedcomParser
{
    private GedcomData $parsedData;

    public function __construct()
    {
        $this->parsedData = new GedcomData();
    }

    /**
     * Parse GEDCOM content into structured data
     */
    public function parse(string $content): GedcomData
    {
        $gedcomData  = [];
        $individuals = [];
        $families    = [];

        $lines         = explode("\n", str_replace(["\r\n", "\r"], "\n", $content));
        $currentRecord = null;

        // Add progress tracking
        $totalLines     = mb_substr_count($content, "\n");
        $processedLines = 0;

        foreach ($lines as $lineNumber => $line) {
            // Progress logging
            if ($processedLines % 1000 === 0) {
                Log::debug("GEDCOM parsing progress: {$processedLines}/{$totalLines} lines");
            }
            $processedLines++;

            $line = mb_trim($line);
            if (empty($line)) {
                continue;
            }

            // Parse GEDCOM line more efficiently
            $parts = explode(' ', $line, 4);
            if (count($parts) < 2) {
                continue;
            }

            $level = (int) $parts[0];

            // Handle level 0 records (new records)
            if ($level === 0) {
                if (count($parts) >= 3) {
                    $possibleId = $parts[1];
                    $tag        = $parts[2];
                    $value      = $parts[3] ?? '';

                    // Check if this is an ID record (starts and ends with @)
                    if (str_starts_with($possibleId, '@') && str_ends_with($possibleId, '@')) {
                        $id = mb_trim($possibleId, '@');

                        if ($tag === 'INDI') {
                            $individuals[$id] = [
                                'id'   => $id,
                                'type' => 'INDI',
                                'data' => [],
                            ];
                            $currentRecord   = &$individuals[$id];
                            $currentRecordId = $id;
                        } elseif ($tag === 'FAM') {
                            $families[$id] = [
                                'id'   => $id,
                                'type' => 'FAM',
                                'data' => [],
                            ];
                            $currentRecord   = &$families[$id];
                            $currentRecordId = $id;
                        } else {
                            // Other ID records (SOUR, OBJE, etc.)
                            $currentRecord   = null;
                            $currentRecordId = null;
                        }
                    } else {
                        // Non-ID level 0 records (HEAD, TRLR)
                        $tag             = $parts[1];
                        $value           = $parts[2];
                        $gedcomData[]    = ['type' => $tag, 'value' => mb_trim($value)];
                        $currentRecord   = null;
                        $currentRecordId = null;
                    }
                }
            } elseif ($level === 1 && $currentRecord !== null) {
                // Level 1 data for current record - add additional safety check
                $tag   = $parts[1];
                $value = implode(' ', array_slice($parts, 2));

                $currentRecord['data'][] = [
                    'tag'   => $tag,
                    'value' => mb_trim($value),
                    'level' => 1,
                    'data'  => [],
                ];
            } elseif ($level === 2 && $currentRecord !== null && ! empty($currentRecord['data'])) {
                // Level 2 data - add to the last level 1 item
                $lastIndex = count($currentRecord['data']) - 1;
                if ($lastIndex >= 0) {
                    $tag   = $parts[1];
                    $value = implode(' ', array_slice($parts, 2));

                    $currentRecord['data'][$lastIndex]['data'][] = [
                        'tag'   => $tag,
                        'value' => mb_trim($value),
                        'level' => 2,
                    ];
                }
            }
            // For higher levels (3+) or when no current record, we skip for now
            // This simplified approach handles the most common GEDCOM structures
        }

        // Remove references to avoid memory issues
        unset($currentRecord);

        // Debug logging to help identify the issue
        Log::info('GEDCOM Parse Complete', [
            'individuals_count' => count($individuals),
            'families_count'    => count($families),
            'families_keys'     => array_keys($families),
        ]);

        // Set parsed data
        $this->parsedData->setGedcomData($gedcomData);
        $this->parsedData->setIndividuals($individuals);
        $this->parsedData->setFamilies($families);

        return $this->parsedData;
    }

    /**
     * Get the parsed data
     */
    public function getParsedData(): ?GedcomData
    {
        return $this->parsedData;
    }
}
