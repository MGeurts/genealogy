<?php

declare(strict_types=1);

// ----------------------------------------------------------------
// Settings helper function
// ----------------------------------------------------------------
if (! function_exists('settings')) {
    function settings(?string $key = null, $default = null): mixed
    {
        if ($key === null) {
            return app('settings');
        }

        return app('settings')->get($key, $default);
    }
}

// ----------------------------------------------------------------
// make some PHP 8.4 functions available in PHP versions < 8.4
// ----------------------------------------------------------------

if (! function_exists('mb_trim')) {
    /**
     * Strip whitespace (or other characters) from the beginning and end of a string
     *
     * @param  string  $string  The string that will be trimmed
     * @param  string  $characters  Optional. The stripped characters. Default is " \n\r\t\v\0"
     * @param  string|null  $encoding  Optional. The encoding parameter is the character encoding
     * @return string The trimmed string
     */
    function mb_trim(string $string, string $characters = " \n\r\t\v\0", ?string $encoding = null): string
    {
        $encoding = $encoding ?? mb_internal_encoding();

        // Escape special regex characters in the characters string
        $chars = preg_quote($characters, '/');

        // Build regex pattern for multibyte characters
        $pattern = '/^[' . $chars . ']+|[' . $chars . ']+$/u';

        return preg_replace($pattern, '', $string);
    }
}

if (! function_exists('mb_ltrim')) {
    function mb_ltrim(string $string, string $characters = " \n\r\t\v\0", ?string $encoding = null): string
    {
        $encoding = $encoding ?? mb_internal_encoding();
        $chars    = preg_quote($characters, '/');
        $pattern  = '/^[' . $chars . ']+/u';

        return preg_replace($pattern, '', $string);
    }
}

if (! function_exists('mb_rtrim')) {
    function mb_rtrim(string $string, string $characters = " \n\r\t\v\0", ?string $encoding = null): string
    {
        $encoding = $encoding ?? mb_internal_encoding();
        $chars    = preg_quote($characters, '/');
        $pattern  = '/[' . $chars . ']+$/u';

        return preg_replace($pattern, '', $string);
    }
}
