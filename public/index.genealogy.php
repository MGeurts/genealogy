<?php

declare(strict_types=1);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../../applications/genealogy/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../../applications/genealogy/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__ . '/../../applications/genealogy/bootstrap/app.php')
    ->handleRequest(Request::capture());
