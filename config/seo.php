<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Here you may specify if you want to enable SEO for your site.
    |
    */
    'enabled' => true,

    'meta' => [

        /*
        |--------------------------------------------------------------------------
        | Robots
        |--------------------------------------------------------------------------
        |
        | Robots option tells search engines what to follow and what not to follow.
        | It's a simple option that gives you the power to decide about what
        | pages you want to hide from search engine crawlers and what pages you
        | want them to index and look at. Get details about robots below
        | https://developers.google.com/search/reference/robots_meta_tag
        |
        */
        'robots' => 'index, follow',

        /*
        |--------------------------------------------------------------------------
        | Revisit After
        |--------------------------------------------------------------------------
        |
        | Here you may specify how search engines will re-visit and re-crawl your site.
        | No longer used by Google
        |
        */
        'revisit_after' => '7 days',

        /*
        |--------------------------------------------------------------------------
        | Referrer
        |--------------------------------------------------------------------------
        |
        | Here you may specify how you want other sites to get referrer information
        | from your site.
        | options available are: none, unsafe-url, origin and none-when-downgrade
        |
        */
        'referrer' => 'no-referrer-when-downgrade',

        /*
        |--------------------------------------------------------------------------
        | Description
        |--------------------------------------------------------------------------
        |
        | Here you may provide the description of your website or a specific page to
        | help search engines understand it better.
        |
        */
        'description' => 'zCart is an awesome multi-vendor platform. You will love it for sure.',

        /*
        |--------------------------------------------------------------------------
        | Description characters limit
        |--------------------------------------------------------------------------
        |
        | Here you may provide the characters limit for description of your website
        |
        */
        'description_character_limit' => '160',

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        |
        | Here you may provide the url to the image you want search
        | engines and crawlers to make use of when displaying your website
        | or a specific page page.
        |
        */
        'image' => '',

        /*
        |--------------------------------------------------------------------------
        | Video
        |--------------------------------------------------------------------------
        |
        | Here you may provide the url to the video you want search
        | engines and crawlers to make use of when displaying your website
        | or a specific page page.
        |
        */
        'video' => '',

        /*
        |--------------------------------------------------------------------------
        | GEO REGION AND POSITION
        |--------------------------------------------------------------------------
        |
        | These are for use if you have a physical location that is important
        | for your business. (No longer used by Google)
        |
        */
        'geo_region' => 'Dhaka', //e.g: Bangkok
        'geo_position' => '23.8103, 90.4125', //e.g(lng,lat): 4.870467,6.993388

        /*
        |--------------------------------------------------------------------------
        | TWITTER SITE
        |--------------------------------------------------------------------------
        |
        | Here you may provide your twitter @username of your account. Example: @incevio
        |
        */
        'twitter_site' => '',

        /*
        |--------------------------------------------------------------------------
        | TWITTER CARD
        |--------------------------------------------------------------------------
        |
        | Here you may specify the way you want crawlers to understand your
        | twitter share type. Check twitter docs for more options.
        |
        */
        'twitter_card' => 'summary_large_image_url',

        /*
        |--------------------------------------------------------------------------
        | FACEBOOK APP ID
        |--------------------------------------------------------------------------
        |
        | Here you may provide your facebook app id. e.g. '316131952302635'
        |
        */
        'fb_app_id' => '',

        /*
        |--------------------------------------------------------------------------
        | KEYWORDS
        |--------------------------------------------------------------------------
        |
        | Here you may provide keywords relevant to your website and the specific page.
        | Not important in Google
        |
        */
        'keywords' => 'incevio,zcart,multi-vendor,multivendor application,laravel ecommerce',

    ],

    /*
     * The values will be used by the sitemap generator
     */
    'sitemap' => [

        /*
         * The sitemap generator uses this arrays to determine
         * which urls should be inlcude for the sitemap.
         */
        'allowed_urls' => [
            'categorygrp',
            'categories',
            'category',
            'locale',
            'product',
            'selling',
            'image',
            'page',
            'shop',
            'brand',
            'blog',
        ],

        /**
         * The time interval to update the sidemap, Default is daily
         */
        'update' => 'daily', // Options are "hourly", "daily", "weekly", "monthly", "yearly"
    ],
];
