<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Genealogy'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),  // don't you dare change this!!!

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    // add all available translations here, after providing the needed translation files in /lang/XX/
    'available_locales' => [
        'Deutsch'    => 'de',               // German
        'English'    => 'en',               // English
        'Español'    => 'es',               // Spanish
        'Français'   => 'fr',               // French
        'Nederlands' => 'nl',               // Dutch
        'Português'  => 'pt',               // Portuguese
        'Türkçe'     => 'tr',               // Turkish
        'Việt Nam'   => 'vi',               // Vietnamese
        '中文简体'   => 'zh_cn',            // Chinees
        'Bahasa Indonesia'   => 'id',       // Indonesian
    ],

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom values used in the application outside of the config files
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'disk'          => env('BACKUP_DISK', 'backups'),
        'daily_cleanup' => env('BACKUP_DAILY_CLEANUP', '22:30'),
        'daily_run'     => env('BACKUP_DAILY_RUN', '23:00'),
        'mail_address'  => env('BACKUP_MAIL_ADDRESS', 'webmaster@yourdomain.com'),
    ],

    // folders where the photos are stored
    'photo_folders' => [
        'photos',
        'photos-096',
        'photos-384',
    ],

    // default values for resizing, watermarking and saving photo uploads
    'upload_photo' => [
        'max_width'     => 600,
        'max_height'    => 800,
        'quality'       => 80,
        'type'          => 'webp',
        'add_watermark' => true,
    ],

    // accepted file types for photo uploads
    'upload_photo_accept' => [
        'image/gif'     => 'GIF',
        'image/jpeg'    => 'JPEG',
        'image/png'     => 'PNG',
        'image/svg+xml' => 'SVG',
        'image/webp'    => 'WEBP',
    ],

    // accepted file types for file uploads
    'upload_file_accept' => [
        'text/plain'                                                              => 'TXT',
        'application/pdf'                                                         => 'PDF',
        'application/vnd.oasis.opendocument.text'                                 => 'ODT',
        'application/msword'                                                      => 'DOC',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'DOCX',
        'application/vnd.ms-excel'                                                => 'XLS',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'       => 'XLSX',
    ],

    'upload_max_size' => 10240, // set this according to your webserver settings (in KB)
];
