<?php

// use Carbon\Carbon;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Search Engine
    |--------------------------------------------------------------------------
    |
    | This option controls the default search connection that gets used while
    | using Laravel Scout. This connection is used when syncing all models
    | to the search service. You should adjust this based on your needs.
    |
    | Supported: "algolia", "null"
    |
    */

    'sales' => [
        // Default reporting time in days
        'default' => 7,
        'take' => 10,
    ],

    // 'dafualt' => env('SCOUT_DRIVER', 'algolia'),

];
