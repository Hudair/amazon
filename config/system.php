<?php

/*
|--------------------------------------------------------------------------
| System configs
|--------------------------------------------------------------------------
|
| The application needs this config file to run properly.
| Dont change any value is you're not sure about it.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription settings
    |--------------------------------------------------------------------------
    |
    | This value will be determind to know if your business uses Subscription model or not
    |
    */
    'subscription' => [
        'enabled' => env('SUBSCRIPTION_ENABLED', true),

        /*
         * Set what is the billing method for subs.
         * Avaialble options are 'stripe','wallet'
         */
        'billing' => env('SUBSCRIPTION_BILLING', 'stripe'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    |
    | Config values for orders. System needs this to manage orders.
    |
    */
    'order' => [
        /**
         * How the vendor get paid order money. The wallet module 
         * required to use other than 'directly' option
         * Avaialble options are 'directly','weekly','monthly'
         */
        'vendor_get_paid' => env('VENDOR_GET_PAID', 'directly'),

        /**
         * When admin get paid but still give option to vendors on/off a active payment method
         */
        'vendor_can_on_off_payment_method' => env('VENDOR_CAN_ON_OFF_PAYMENT_METHOD', FALSE),
    ],

    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    |
    | Config for checkout. System needs this to manage Checkout.
    |
    */
    'checkout' => [
        /*
         * How the money route on checkout. The wallet module required to use 'wallet' option
         * Avaialble options are 'vendor','wallet'
         */
        'route' => env('ORDER_CHECKOUT_ROUTE', 'vendor'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    |
    | Config for customer. System needs this to manage Customer.
    |
    */
    'customer' => [
        'has_wallet' => env('CUSTOMER_HAS_WALLET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | CSV Import Limit
    |--------------------------------------------------------------------------
    |
    | This much records can be uploaded in a single batch in csv upload inventories/products
    |
    */
    'csv_import_limit' => 500,

    /*
    |--------------------------------------------------------------------------
    | Import Required
    |--------------------------------------------------------------------------
    |
    | This fields are required to csv upload
    |
    */
    'import_required' => [
        'product' => ['name', 'categories', 'gtin', 'gtin_type'],
        'inventory' => ['title', 'description', 'sku', 'gtin', 'gtin_type', 'stock_quantity', 'condition'],
        'customer' => ['full_name', 'email', 'temporary_password', 'accepts_marketing', 'active'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory
    |--------------------------------------------------------------------------
    |
    | Config values for inventory. System needs this to manage inventory.
    |
    */
    'inventory' => [
        'max_key_features' => 7, // Maximum Number of key features can be added when creating an inventory
    ],

    /*
    |--------------------------------------------------------------------------
    | Non decimal currencies
    |--------------------------------------------------------------------------
    |
    | List of non decimal currencies
    |
    */
    'non_decimal_currencies' => ['JPY'],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Number of product will be display on the product lisitng page and search result.
    |
    */
    'view_listing_per_page' => 15,
    'view_blog_post_per_page' => 4,

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
            'daily'     => 1,
            'weekly'    => 7,
            'monthly'   => 30,
        ],

        // Number of top selling products will be picked
        'take' => [
            'trending'  => 20,
            'daily'     => 5,
            'weekly'    => 5,
            'monthly'   => 5,
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
        'new_arrival' => 7, //Days
    ],

    /*
    |--------------------------------------------------------------------------
    | Freezed models
    |--------------------------------------------------------------------------
    |
    | This IDs associated with the models are not deletable, sometimes not editable.
    |
    */
    'freeze' => [
        'pages' => [1, 2, 3, 4, 5, 6],
        'languages' => [1],
    ],

    /*
    |--------------------------------------------------------------------------
    | Demo Mode
    |--------------------------------------------------------------------------
    |
    | This values will be used for the demo mode settings. You dont have to change these
    |
    */
    'demo' => [
        'users' => 3,
        'roles' => 3,
        'shops' => 2,
        'langs' => 4,
        'customers' => 1,
        'delivery_boys' => 5,
        'category_groups' => 9,
        'plans' => ['business', 'individual', 'professional'],
        'slider_negative_margin' => [3, 4],
    ],

];
