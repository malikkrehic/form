<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Form Package Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the Mk Form package.
    | You can publish this config file and modify the values as needed.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Form Configuration
    |--------------------------------------------------------------------------
    |
    | These are the default configuration values used when creating forms.
    | You can override these in individual form classes.
    |
    */
    'defaults' => [
        'method' => 'POST',
        'configuration' => [
            'width' => 'max-w-2xl',
            'submitLabel' => 'Submit',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for form routes and middleware.
    |
    */
    'routes' => [
        'prefix' => 'forms',
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-register Forms
    |--------------------------------------------------------------------------
    |
    | If true, the package will attempt to auto-register forms from
    | configured directories. Set to false to disable.
    |
    */
    'auto_register' => false,

    /*
    |--------------------------------------------------------------------------
    | Form Directories
    |--------------------------------------------------------------------------
    |
    | Directories to scan for forms when auto_register is enabled.
    | These paths will be resolved using Laravel's app_path() helper.
    |
    */
    'directories' => [
        'app/Forms', 
        'app/Http/Forms',
    ],
];
