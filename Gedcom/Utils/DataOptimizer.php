<?php

declare(strict_types=1);

namespace Gedcom\Utils;

final class DataOptimizer
{
    public static function trimString(string $input): string
    {
        return trim($input);
    }

    public static function normalizeIdentifier(string $identifier): string
    {
        return trim(self::trimString($identifier), '@');
    }

    public static function concatenateWithSeparator(array $strings, string $separator = ' '): string
    {
        return implode($separator, array_map(fn(string $str) => self::trimString($str), $strings));
    }
}
/**
 * Provides static utility methods for string and data handling.
 *
 * The DataOptimizer class is a collection of static methods aimed at optimizing and manipulating data,
 * especially strings, to ensure consistency and efficiency within the GEDCOM project.
 */
/**
 * Trims whitespace from the beginning and end of a string.
 *
 * @param string $input The input string to be trimmed.
 * @return string The trimmed string.
 *
 * Example:
 * ```php
 * $trimmedString = DataOptimizer::trimString("  example string  ");
 * // Returns "example string"
 * ```
 */
/**
 * Normalizes a GEDCOM identifier by trimming whitespace and '@' characters.
 *
 * @param string $identifier The identifier to be normalized.
 * @return string The normalized identifier.
 *
 * Example:
 * ```php
 * $normalizedId = DataOptimizer::normalizeIdentifier("@I001@");
 * // Returns "I001"
 * ```
 */
/**
 * Concatenates an array of strings with a specified separator after trimming each string.
 *
 * @param array $strings The array of strings to concatenate.
 * @param string $separator The separator to use between each string. Defaults to a single space.
 * @return string The concatenated string.
 *
 * Example:
 * ```php
 * $concatenatedString = DataOptimizer::concatenateWithSeparator(["  first ", "second  ", " third "], ", ");
 * // Returns "first, second, third"
 * ```
 */