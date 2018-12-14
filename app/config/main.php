<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    |
    */

    'storage' => [
        'url' => env('STORAGE_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Frontend Application Configuration
    |--------------------------------------------------------------------------
    |
    */

    'frontend' => [
        'url' => env('FRONTEND_APP_URL', 'http://localhost:8080'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    */

    'upload' => [
        'min-size' => 1, // kilobytes
        'max-size' => 20480, // kilobytes
        'min-image-width' => 600, // pixels
        'min-image-height' => 600, // pixels
    ],

    /*
    |--------------------------------------------------------------------------
    | Photo Configuration
    |--------------------------------------------------------------------------
    |
    */

    'photo' => [
        'thumbnails' => [
            [
                'mode' => 'inset',
                'quality' => 70,     // percentage
                'prefix' => 'large',
                'width' => 2000,     // pixels
                'height' => 2000,    // pixels
            ],
            [
                'mode' => 'inset',
                'quality' => 70,    // percentage
                'prefix' => 'medium',
                'width' => 600,     // pixels
                'height' => 600,    // pixels
            ],
        ],
    ],

];
