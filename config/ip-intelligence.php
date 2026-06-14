<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | To ensure high performance, geolocation results from external APIs
    | are cached. You can configure the cache duration (in seconds)
    | or disable caching completely by setting 'enabled' to false.
    |
    */

    'cache' => [
        'enabled' => env('IP_INTELLIGENCE_CACHE_ENABLED', true),
        'ttl'     => env('IP_INTELLIGENCE_CACHE_TTL', 604800), // 7 days (1 week)
        'prefix'  => 'ip_intelligence:',
    ],

];
