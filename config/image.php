<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Resize Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for image resizing dimensions
    | used throughout the application.
    |
    */

    'dimensions' => [
        'banner' => [
            'width' => 1232,
            'height' => 320,
        ],
        'package' => [
            'width' => 400,
            'height' => 300,
        ],
        'article' => [
            'width' => 400,
            'height' => 300,
        ],
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
        ],
        'medium' => [
            'width' => 400,
            'height' => 300,
        ],
        'large' => [
            'width' => 800,
            'height' => 600,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Quality
    |--------------------------------------------------------------------------
    |
    | Set the default quality for image compression (1-100)
    | Higher values mean better quality but larger file sizes
    |
    */
    'quality' => 85,

    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | Supported drivers: "gd", "imagick"
    | GD is more widely available, Imagick offers more features
    |
    */
    'driver' => 'gd',
];
