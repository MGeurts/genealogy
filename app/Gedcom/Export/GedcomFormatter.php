<?php

declare(strict_types=1);

namespace App\Gedcom\Export;

use Carbon\CarbonInterface;

// ==============================================================================
// GEDCOM FORMATTER - Common formatting utilities and text processing
// ==============================================================================

/**
 * GEDCOM Formatter Class
 *
 * Centralizes all text formatting, sanitization, and GEDCOM-specific
 * formatting operations used across all record builders.
 */
class GedcomFormatter
{
    // --------------------------------------------------------------------------------------
    // TEXT FORMATTING UTILITIES
    // --------------------------------------------------------------------------------------

    /**
     * Get appropriate line ending for GEDCOM format.
     *
     * @return string Line ending characters
     */
    public function eol(): string
    {
        return "\r\n";
    }

    /**
     * Sanitize text for GEDCOM output.
     *
     * Removes line breaks and control characters, limits length to GEDCOM specs.
     *
     * @param  string  $text  Text to sanitize
     * @return string Sanitized text
     */
    public function sanitizeText(string $text): string
    {
        // Remove line breaks and control characters, limit length
        $sanitized = preg_replace('/[\r\n\t]/', ' ', mb_trim($text));

        return mb_substr($sanitized, 0, 248); // GEDCOM line length limit
    }

    /**
     * Collapse multi-line text to a single GEDCOM-safe line.
     *
     * @param  string  $text  Multi-line text
     * @return string Single line text
     */
    public function oneLine(string $text): string
    {
        return mb_trim(preg_replace('/\s+/', ' ', str_replace(["\r", "\n"], ' ', $text)));
    }

    // --------------------------------------------------------------------------------------
    // DATE AND COORDINATE FORMATTING
    // --------------------------------------------------------------------------------------

    /**
     * Format a date for GEDCOM output.
     *
     * @param  CarbonInterface  $date  Date to format
     * @return string Formatted GEDCOM date
     */
    public function formatGedcomDate(CarbonInterface $date): string
    {
        return mb_strtoupper($date->format('j M Y'));
    }

    /**
     * Format coordinates for GEDCOM output.
     * GEDCOM 7.0 uses specific coordinate format requirements.
     *
     * @param  string|float  $coordinate  Coordinate value
     * @param  string  $type  'latitude' or 'longitude'
     * @return string Formatted GEDCOM coordinate
     */
    public function formatGedcomCoordinate($coordinate, string $type): string
    {
        $coord = (float) $coordinate;

        if ($type === 'latitude') {
            $direction = $coord >= 0 ? 'N' : 'S';
        } else {
            $direction = $coord >= 0 ? 'E' : 'W';
        }

        // Absolute value for degrees
        $degrees = abs($coord);

        // GEDCOM 7 prefers up to 5 decimal places for precision
        return sprintf('%s%.5f', $direction, $degrees);
    }

    // --------------------------------------------------------------------------------------
    // MULTI-LINE TEXT HANDLING
    // --------------------------------------------------------------------------------------

    /**
     * Export a multi-line text field with CONC/CONT support.
     *
     * Handles proper GEDCOM formatting for multi-line fields using
     * CONT (continuation) and CONC (concatenation) tags as per GEDCOM 7.0 spec.
     *
     * @param  string  $tag  GEDCOM tag (NOTE, ADDR, OCCU, EDUC, etc.)
     * @param  string  $text  Multi-line text
     * @param  int  $level  Base GEDCOM level
     * @return array<string> Array of GEDCOM lines
     */
    public function exportMultilineText(string $tag, string $text, int $level = 1): array
    {
        $lines = [];
        $parts = preg_split('/\r\n|\r|\n/', $text);

        if (empty($parts)) {
            return $lines;
        }

        foreach ($parts as $i => $line) {
            $line = $this->sanitizeText($line);

            // First line → main tag
            $lineText = $i === 0 ? "{$level} {$tag} {$line}" : ($level + 1) . ' CONT ' . $line;

            // Split long lines > 255 chars using CONC
            while (mb_strlen($lineText) > 255) {
                $lines[]  = mb_substr($lineText, 0, 255);
                $lineText = ($level + 1) . ' CONC ' . mb_substr($lineText, 255);
            }

            $lines[] = $lineText;
        }

        return $lines;
    }
}
