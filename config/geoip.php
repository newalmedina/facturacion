<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default GeoIP Service
    |--------------------------------------------------------------------------
    */
    'service' => env('GEOIP_SERVICE', 'ipapi'),

    /*
    |--------------------------------------------------------------------------
    | Service Configurations
    |--------------------------------------------------------------------------
    */
    'services' => [

        'ipapi' => [
            'class' => \Torann\GeoIP\Services\IPApi::class,
            'url' => 'http://ip-api.com/json/{ip}',
        ],

        // otros servicios ...
    ],

    // más config...
];
