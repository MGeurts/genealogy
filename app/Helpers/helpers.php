<?php

declare(strict_types=1);

// ----------------------------------------------------------------
// Settings helper function
// ----------------------------------------------------------------
if (! function_exists('settings')) {
    function settings(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return app('settings');
        }

        return app('settings')->get($key, $default);
    }
}
