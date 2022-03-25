<?php

/*
|--------------------------------------------------------------------------
| Mobile app configs
|--------------------------------------------------------------------------
|
| The mobile application needs this config file to run properly.
| Dont change any value is you're not sure about it.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Number of product will be display on the product lisitng page and search result.
    |
    */
    'view_listing_per_page' => 8,
    // 'view_blog_post_per_page' => 4,

    /*
    |--------------------------------------------------------------------------
    | Popular
    |--------------------------------------------------------------------------
    |
    | This values (Days) will be used to pick popular products.
    |
    */
    'popular' => [
        // Number of Days
        'period' => [
            'trending'  => 2,
            'weekly'    => 7,
        ],
        // Number of top selling products will be picked
        'take' => [
            'trending'  => 8,
            'weekly'    => 8,
        ],

        // This will use to lebel product list as hot item
        'hot_item' => [
            'period'        => 24, //hrs
            'sell_count'    => 3,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    |
    | This values (Days) will be used to filter product lintings.
    |
    */
    'filter' => [
        'new_arrival' => 7, // Days
    ],

    /*Push Notification For Mobile App*/
    'push_notification' => [
        'notify' => true,
        'group_notify' => true,
        'group' => 'test users',
        'class' => \App\Models\Customer::class,
        'app_id' => env('PUSHER_APP_ID'),
        'api_key' => env('PUSHER_APP_KEY'),
    ],

];
