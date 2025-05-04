<?php

namespace Gedcom;

class FormatInformation
{
    public static function addFormatInformation(string $format): string
    {
        // Generate and return the format information based on the specified format
        switch ($format) {
            case 'XML':
                return "<format>XML</format>\n";
            case 'JSON':
                return "{ \"format\": \"JSON\" }\n";
            default:
                return "Format: $format\n";
        }
    }
}
/**
 * Adds format information to the output based on the specified format.
 *
 * Supported formats:
 * - XML: Returns a string with XML format tag.
 * - JSON: Returns a string with JSON format object.
 * - Default: Returns a simple string indicating the format.
 *
 * @param string $format The output format.
 * @return string The format information string.
 */
