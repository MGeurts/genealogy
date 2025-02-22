<?php

declare(strict_types=1);

if (! function_exists('settings')) {
    function settings(?string $key = null, $default = null)
    {
        if ($key === null) {
            return app('settings');
        }

        return app('settings')->get($key, $default);
    }
}
