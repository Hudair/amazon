<?php

use App\Helpers\ListHelper;
use App\Models\Announcement;
use App\Models\Cancellation;
use App\Models\Category;
use App\Models\ChatConversation;
use App\Models\Country;
use App\Models\Customer;
use App\Models\DeliveryBoy;
use App\Models\Dispute;
use App\Models\Inventory;
use App\Models\Manufacturer;
use App\Models\Message;
use App\Models\Order;
use App\Models\Packaging;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ShippingRate;
use App\Models\Shop;
use App\Models\State;
use App\Models\System;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

// if (! function_exists('get_platform_tld'))
// {
//     /**
//      * Return shop title or the application title
//      */
//     function get_platform_tld()
//     {
//         $url = parse_url(config('app.url'));

//         return $url['host'];
//     }
// }

if (!function_exists('get_platform_title')) {
    /**
     * Return shop title or the application title
     */
    function get_platform_title()
    {
        return config('system_settings.name') ?? config('app.name');
    }
}

if (!function_exists('get_platform_address')) {
    /**
     * return platforms address in html formate
     */
    function get_platform_address()
    {
        $system = System::orderBy('id', 'asc')->first();

        return $system->primaryAddress->toHtml();
    }
}

//Get address as array:
if (!function_exists('get_platform_address_string')) {
    /**
     * return platforms address in html formate
     */
    function get_platform_address_string()
    {
        // Retrive and set to Cache
        return Cache::rememberForever('platform_address_string', function () {
            $system = System::orderBy('id', 'asc')->first();

            return $system->primaryAddress ? $system->primaryAddress->toString() : '';
        });
    }
}

if (!function_exists('system_cache_remember_for')) {
    /**
     * Return cache time
     */
    function system_cache_remember_for($minute = 10)
    {
        return Carbon::now()->addMinutes($minute);
    }
}

if (!function_exists('get_site_title')) {
    /**
     * Return shop title or the application title
     */
    function get_site_title()
    {
        if (Auth::guard('web')->check() && Auth::user()->isFromMerchant() && Auth::user()->shop) {
            return Auth::user()->shop->name;
        }

        return get_platform_title();
    }
}

if (!function_exists('get_system_currency')) {
    function get_system_currency()
    {
        return config('system_settings.currency.iso_code');
    }
}

if (!function_exists('is_billing_info_required')) {
    function is_billing_info_required()
    {
        return is_subscription_enabled() && config('system_settings.required_card_upfront');
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol()
    {
        return config('system_settings.currency.symbol', '$');
    }
}

if (!function_exists('get_global_announcement')) {
    function get_global_announcement()
    {
        return Cache::rememberForever('global_announcement', function () {
            return Announcement::orderBy('created_at', 'desc')->first();
        });
    }
}

if (!function_exists('get_promotional_tagline')) {
    function get_promotional_tagline()
    {
        return Cache::rememberForever('promotional_tagline', function () {
            return get_from_option_table('promotional_tagline', []);
        });
    }
}

if (!function_exists('get_option_table_name')) {
    function get_option_table_name()
    {
        return 'options';
    }
}

if (!function_exists('get_social_media_links')) {
    /**
     * Return social_media_links
     */
    function get_social_media_links()
    {
        $media = ['facebook', 'twitter', 'google_plus', 'pinterest', 'instagram', 'youtube'];
        $links = [];
        foreach ($media as $value) {
            if ($link = config('system_settings.' . $value . '_link')) {
                $links[str_replace('_', '-', $value)] = $link;
            }
        }

        return $links;
    }
}

if (!function_exists('get_shop_url')) {
    /**
     * Return shop title or the application title
     */
    function get_shop_url($id = null)
    {
        $slug = '';
        if (Auth::user()->isFromMerchant() && Auth::user()->shop) {
            $slug = Auth::user()->shop->slug;
        } elseif (Auth::user()->isFromPlatform() && $id) {
            $slug = DB::table('shops')->find($id)->slug;
        }

        if ($slug != '') {
            return url('/shop/' . $slug);
        }

        return url('/');
    }
}

if (!function_exists('get_csv_import_limit')) {
    /**
     * Return the csv_import_limit
     */
    function get_csv_import_limit()
    {
        return config('system_settings.csv_import_limit') ?? config('system.csv_import_limit', 500);
    }
}

if (!function_exists('get_page_url')) {
    /**
     * Return page url
     */
    function get_page_url($page = null)
    {
        if ($page == null) {
            return url('/');
        }

        return route('page.open', $page);
    }
}

if (!function_exists('get_verified_badge')) {
    function get_verified_badge()
    {
        return url('images/placeholders/verified_badge.png');
    }
}

if (!function_exists('get_invoice_stamp')) {
    /**
     * Return invoice stamp img
     */
    function get_invoice_stamp()
    {
        return public_path('images/placeholders/stamp.png');
    }
}

if (!function_exists('is_serialized')) {
    /**
     * Check if the given value is_serialized or not
     */
    function is_serialized($data)
    {
        // if it isn't a string, it isn't serialized
        if (!is_string($data)) {
            return false;
        }

        $data = trim($data);

        if ('N;' == $data) {
            return true;
        }

        if (!preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }

        switch ($badions[1]) {
            case 'a':
            case 'O':
            case 's':
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;
            case 'b':
            case 'i':
            case 'd':
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }

        return false;
    }
}

if (!function_exists('remove_url_parameter')) {
    /**
     * Remove given parameter from the given url str
     */
    function remove_url_parameter($url, $key = false)
    {
        return preg_replace('/' . ($key ? '(\&|)' . $key . '(\=(.*?)((?=&(?!amp\;))|$)|(.*?)\b)' : '(\?.*)') . '/i', '', $url);
    }
}

if (!function_exists('get_avatar_src')) {
    function get_avatar_src($model, $size = 'small')
    {
        if (
            $model instanceof User ||
            $model instanceof Customer ||
            $model instanceof DeliveryBoy
        ) {
            if ($model->avatar) {
                return get_storage_file_url($model->avatar->path, $size);
            }

            return get_gravatar_url($model->email, $size);
        }

        return get_gravatar_url('help@incevio.com', $size);
    }
}

if (!function_exists('get_gravatar_url')) {
    function get_gravatar_url($email, $size = 'small')
    {
        $size = config("image.sizes.{$size}");

        $email = md5(strtolower(trim($email)));

        return "https://www.gravatar.com/avatar/{$email}?s={$size['w']}&d=mm";
    }
}

if (!function_exists('get_sender_email')) {
    /**
     * Return shop title or the application title
     */
    function get_sender_email($shop = null)
    {
        if ($shop) {
            return config('shop_settings.default_sender_email_address') ??
                config('mail.from.address');
        }

        return config('mail.from.address');

        // return config('system_settings.default_sender_email_address') ??
        //     get_value_from(1, 'systems', 'default_sender_email_address') ??
        //     config('mail.from.address');
    }
}

if (!function_exists('get_sender_name')) {
    /**
     * Return shop title or the application title
     */
    function get_sender_name($shop = null)
    {
        if ($shop) {
            return config('shop_settings.default_email_sender_name') ??
                config('mail.from.name');
        }

        return config('mail.from.name');

        // return config('system_settings.default_email_sender_name') ??
        //     get_value_from(1, 'systems', 'default_email_sender_name') ??
        //     config('mail.from.name');
    }
}

if (!function_exists('get_address_str_from_request_data')) {
    function get_address_str_from_request_data($request)
    {
        $state = is_numeric($request->state_id) ? get_value_from($request->state_id, 'states', 'name') : $request->state_id;

        $str = [];
        $str[] = '<address>';
        $str[] = $request->address_title;
        $str[] = $request->address_line_1;
        $str[] = $request->address_line_2;
        $str[] = $request->city;
        $str[] = $state . ' ' . $request->zip_code;
        $str[] = is_numeric($request->country_id) ? get_value_from($request->country_id, 'countries', 'name') : $request->country_id;

        if ($request->phone) {
            $str[] = trans('app.phone') . ': ' . e($request->phone);
        }
        $str[] = '</address>';

        return implode(',<br/>', array_filter($str));
    }
}

if (!function_exists('address_str_to_html')) {
    function address_str_to_html($address, $separator = '<br/>')
    {
        $addressStr = str_replace(',', $separator, $address);

        return '<address>' . $addressStr . '</address>';
    }
}

if (!function_exists('address_str_to_geocode_str')) {
    function address_str_to_geocode_str($address)
    {
        $t_arr = explode(',', $address);
        array_shift($t_arr); // Remove address titme/name

        // Remove phone number from address
        if (preg_match('/^[0-9 +-]*$/', end($t_arr))) {
            array_pop($t_arr);
        }

        // build str string
        $str = trim(implode(',', array_filter($t_arr)));

        return str_replace(' ', '+', $str);
    }
}

/**
 * Get latitude and longitude of an address from Google API
 */
if (!function_exists('getGeocode')) {
    function getGeocode($address)
    {
        if (is_object($address)) {
            $address = $address->toGeocodeString();
        } elseif (is_numeric($address)) {
            $address = DB::table('addresses')->find($address);
            $address = $address->toGeocodeString();
        }

        $url = 'https://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false';

        $result = [];

        // try to get geo codes
        if ($geocode = file_get_contents($url)) {
            $output = json_decode($geocode);

            if (count($output->results) && isset($output->results[0])) {
                if ($geo = $output->results[0]->geometry) {
                    $result['latitude'] = $geo->location->lat;
                    $result['longitude'] = $geo->location->lng;
                }
            }
        }

        return $result;
    }
}

if (!function_exists('getPaginationValue')) {
    function getPaginationValue()
    {
        if (Auth::user()->isFromPlatform()) {
            return config('system_settings.pagination') ?? 10;
        }

        return config('shop_settings.pagination') ?? 10;
    }
}

if (!function_exists('getMinNumberOfRequiredImgsForInventory')) {
    /**
     * Return Min Number Of Required Imgs For Inventory to upload per item
     */
    function getMinNumberOfRequiredImgsForInventory()
    {
        return config('system_settings.min_number_of_inventory_imgs', 0);
    }
}

if (!function_exists('getMaxNumberOfImgsForInventory')) {
    /**
     * Return max_number_of_inventory_imgs allowed to upload per item
     */
    function getMaxNumberOfImgsForInventory()
    {
        return config('system_settings.max_number_of_inventory_imgs', 10);
    }
}

if (!function_exists('getAllowedMinImgSize')) {
    /**
     * Return min_img_size_limit_kb allowed to upload
     */
    function getAllowedMinImgSize()
    {
        return config('system_settings.min_img_size_limit_kb') ?? config('image.min_size', 0);
    }
}

if (!function_exists('getAllowedMaxImgSize')) {
    /**
     * Return max_img_size_limit_kb allowed to upload
     */
    function getAllowedMaxImgSize()
    {
        return config('system_settings.max_img_size_limit_kb') ?? config('image.max_size', 1024);
    }
}

if (!function_exists('allow_checkout')) {
    function allow_checkout()
    {
        return config('system_settings.allow_guest_checkout') || Auth::guard('customer')->check();
    }
}

if (!function_exists('highlightWords')) {
    function highlightWords($content = null, $words = null)
    {
        if ($content == null || $words == null) {
            return $content;
        }

        if (is_array($words)) {
            foreach ($words as $word) {
                $content = str_ireplace($word, '<mark>' . $word . '</mark>', $content);
            }

            return $content;
        }

        return str_ireplace($words, '<mark>' . $words . '</mark>', $content);
    }
}

if (!function_exists('clear_encoding_str')) {
    function clear_encoding_str($value)
    {
        if (is_array($value)) {
            $clean = [];

            foreach ($value as $key => $val) {
                $clean[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
            }

            return $clean;
        }

        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }
}

if (!function_exists('get_qualified_model')) {
    function get_qualified_model($class_name = '')
    {
        return 'App\\Models\\' . Str::singular(Str::studly($class_name));
    }
}

if (!function_exists('temp_storage_dir')) {
    function temp_storage_dir($dir = '')
    {
        return Str::finish(public_path("temp/{$dir}"), '/');
    }
}

if (!function_exists('attachment_storage_dir')) {
    function attachment_storage_dir($dir = '')
    {
        return 'attachments';
        // return Str::finish("attachments/{$dir}", '/');
    }
}

if (!function_exists('image_storage_dir')) {
    function image_storage_dir()
    {
        return config('image.dir');
    }
}

if (!function_exists('sys_image_path')) {
    function sys_image_path($dir = '')
    {
        return Str::finish("images/{$dir}", '/');
    }
}

if (!function_exists('image_storage_path')) {
    function image_storage_path($path = null)
    {
        $path = image_storage_dir() . '/' . $path;

        return Str::finish($path, '/');
    }
}

if (!function_exists('image_cache_path')) {
    function image_cache_path($path = null)
    {
        $path = config('image.cache_dir') . '/' . $path;

        return Str::finish($path, '/');
    }
}

if (!function_exists('get_storage_file_url')) {
    function get_storage_file_url($path = null, $size = 'small')
    {
        if (!$path) {
            return get_placeholder_img($size);
        }

        if ($size == null) {
            return url("image/{$path}");
        }

        return url("image/{$path}?p={$size}");
    }
}

if (!function_exists('get_placeholder_img')) {
    function get_placeholder_img($size = 'small', $txt = null)
    {
        $size = config("image.sizes.{$size}");

        $txt = $txt ?? get_platform_title();

        if ($size && is_array($size)) {
            return "https://via.placeholder.com/{$size['w']}x{$size['h']}/eee?text=" . $txt;
        }

        return url('images/placeholders/no_img.png');
    }
}

if (!function_exists('get_product_img_src')) {
    function get_product_img_src($item = null, $size = 'medium', $type = 'primary')
    {
        if (!$item) {
            return asset('images/placeholders/no_img.png');
        }

        if (is_numeric($item) && !($item instanceof Inventory)) {
            $item = Inventory::findorFail($item);
        }

        $images_count = $item->images->count();

        // If the listing has no images then pick the product images
        if (!$images_count) {
            $item = $item->product;
            $images_count = $item->images->count();
        }

        if ($images_count) {
            if ($type == 'alt' && $images_count > 1) {
                $imgs = $item->images->toArray();
                $path = $imgs[1]['path'];
            } else {
                $path = $item->images->first()->path;
            }

            return url("image/{$path}?p={$size}");
        }

        return asset('images/placeholders/no_img.png');
    }
}

if (!function_exists('get_inventory_img_src')) {
    function get_inventory_img_src($item, $size = 'medium')
    {
        if ($item->image) {
            return get_storage_file_url($item->image->path, $size);
        }

        if ($item->product->image) {
            return get_storage_file_url($item->product->image->path, $size);
        }

        return asset('images/placeholders/no_img.png');
    }
}

if (!function_exists('get_catalog_featured_img_src')) {
    function get_catalog_featured_img_src($product, $size = 'small')
    {
        if (is_int($product) && !($product instanceof Product)) {
            $product = Product::findorFail($product);
        }

        if ($product->featureImage) {
            return get_storage_file_url($product->featureImage->path, $size);
        }

        // if ($product->featuredImage) {
        //     return get_storage_file_url($product->featuredImage->path, $size);
        // }

        if ($product->image) {
            return get_storage_file_url($product->image->path, $size);
        }

        return asset('images/placeholders/no_img.png');
    }
}

if (!function_exists('get_cover_img_src')) {
    function get_cover_img_src($model, $type = 'category')
    {
        if (isset($model->coverImage->path) && Storage::exists($model->coverImage->path)) {
            return get_storage_file_url($model->coverImage->path, 'cover');
        }

        return asset('images/placeholders/' . $type . '_cover.jpg');
    }
}

if (!function_exists('get_logo_url')) {
    function get_logo_url($model, $size = 'small')
    {
        if ($model == 'platform') {
            return Storage::exists('logo.png') ? get_storage_file_url('logo.png', $size) : get_placeholder_img('logo');
        } elseif ($model->logoImage) {
            return get_storage_file_url($model->logoImage->path, $size);
        }

        return get_placeholder_img($size);
    }
}

if (!function_exists('get_trust_badge_url')) {
    function get_trust_badge_url()
    {
        if (Storage::exists('trust_badge.png')) {
            return get_storage_file_url('trust_badge.png', 'full');
        }

        return null;
    }
}

if (!function_exists('verifyUniqueSlug')) {
    function verifyUniqueSlug($slug, $table, $field = 'slug', $json = true)
    {
        if (DB::table($table)->select($field)->where($field, $slug)->first()) {
            return $json ? response()->json('false') : false;
        }

        return $json ? response()->json('true') : true;
    }
}

if (!function_exists('convertToSlugString')) {
    function convertToSlugString($str, $salt = null, $separator = '-')
    {
        if ($salt) {
            return Str::slug($str, $separator) . $separator . Str::slug($salt, $separator);
        }

        return Str::slug($str, $separator);
    }
}

if (!function_exists('generateCouponCode')) {
    function generateCouponCode()
    {
        $unique = true;
        $size = config('system_settings.coupon_code_size');

        do {
            $code = generateUniqueSrt($size);

            $check = DB::table('coupons')->where('code', $code)->first();

            if ($check) {
                $unique = false;
            }
        } while (!$unique);

        return $code;
    }
}

if (!function_exists('generatePinCode')) {
    function generatePinCode()
    {
        $unique = true;
        $size = config('system_settings.gift_card_pin_size');

        do {
            $code = generateUniqueSrt($size);

            $check = DB::table('gift_cards')->where('pin_code', $code)->first();

            if ($check) {
                $unique = false;
            }
        } while (!$unique);

        return $code;
    }
}

if (!function_exists('generateSerialNumber')) {
    function generateSerialNumber()
    {
        $unique = true;
        $size = config('system_settings.gift_card_serial_number_size');

        do {
            $code = generateUniqueSrt($size);

            $check = DB::table('gift_cards')->where('serial_number', $code)->first();

            if ($check) {
                $unique = false;
            }
        } while (!$unique);

        return $code;
    }
}

if (!function_exists('generateUniqueSrt')) {
    /**
     * Generate random alfa numaric str.
     *
     * @param  string $dob date of bith
     *
     * @return string
     */
    function generateUniqueSrt($size = 8)
    {
        $characters = implode(range('A', 'Z')) . implode(range(0, 9));
        $uniqueStr = '';
        for ($i = 0; $i < $size; $i++) {
            $uniqueStr .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $uniqueStr;
    }
}

if (!function_exists('get_age')) {
    /**
     * Get age of user/customer from date of birth.
     * @param  string $dob date of bith
     * @return string
     */
    function get_age($dob)
    {
        return date_diff(date_create($dob), date_create('today'))->y . ' years old';
    }
}

if (!function_exists('get_readble_time_from_secconts')) {
    function get_readble_time_from_secconts($seconds = null)
    {
        if (!$seconds) {
            return '';
        }

        $s = $seconds % 60;
        $m = floor(($seconds % 3600) / 60);
        $h = floor(($seconds % 86400) / 3600);
        $d = floor(($seconds % 2592000) / 86400);
        $M = floor($seconds / 2592000);

        $time = '';
        if ($M > 0) {
            $time .= $M . ' ' . trans_choice('app.months', $M) . ', ';
        }

        if ($d > 0) {
            $time .= $d . ' ' . trans_choice('app.days', $d) . ', ';
        }

        if ($h > 0) {
            $time .= $h . ' ' . trans_choice('app.hours', $h) . ', ';
        }

        if ($m > 0) {
            $time .= $m . ' ' . trans_choice('app.minutes', $m) . ', ';
        }

        if ($s > 0) {
            $time .= $s . ' ' . trans_choice('app.seconds', $s) . ', ';
        }

        return substr($time, 0, -2);
    }
}

if (!function_exists('get_formated_file_size')) {
    /**
     * Get the formated file size.
     * @param  int $bytes
     * @param  int $precision
     * @return string formated size string
     */
    function get_formated_file_size($bytes = 0, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('get_customer_email_from_order')) {
    function get_customer_email_from_order($order)
    {
        if (!$order instanceof Order) {
            $order = Order::find($order);
        }

        if ($order->customer->email) {
            return $order->customer->email;
        }

        return $order->email;
    }
}

if (!function_exists('get_formated_cutomer_str')) {
    /**
     * Get the formated customer string.
     *
     * @param  object|array $customer
     *
     * @return string      formated customer string
     */
    function get_formated_cutomer_str($customer)
    {
        if (is_array($customer)) {
            return  $customer['nice_name'] . ' | ' . $customer['name'] . ' | ' . $customer['email'];
        }

        return  $customer->nice_name . ' | ' . $customer->name . ' | ' . $customer->email;
    }
}

if (!function_exists('get_formated_gender')) {
    /**
     * Get the formated gender string.
     *
     * @param  string $sex
     *
     * @return string      formated gender to display
     */
    function get_formated_gender($sex)
    {
        $icon = '';
        if ('Male' == $sex || 'app.male' == $sex) {
            $icon = "<i class='fa fa-mars'></i> ";
        } elseif ('Female' == $sex || 'app.female' == $sex) {
            $icon = "<i class='fa fa-venus'></i> ";
        }

        return $icon . trans($sex);
    }
}

if (!function_exists('get_cent_from_doller')) {
    /**
     * Get cent from decimal amount value.
     *
     * @param  decimal $value
     *
     * @return int
     */
    function get_cent_from_doller($value = 0)
    {
        $decimals = 2;

        if (in_array(get_system_currency(), config('system.non_decimal_currencies'))) {
            $decimals = 0;
        }

        $value = number_format($value, $decimals, '.', '');

        return intval($value * 100);
    }
}

if (!function_exists('get_doller_from_cent')) {
    /**
     * Get doller from cent decimal value.
     *
     * @param  decimal $value
     *
     * @return int
     */
    function get_doller_from_cent($value = 0)
    {
        if (!is_int($value)) {
            $value = intval($value);
        }

        $value = $value / 100;

        return number_format($value, 2, config('system_settings.currency.decimal_mark', '.'), '');
    }
}

if (!function_exists('format_to_number')) {
    /**
     * Format the input data with decimal places
     *
     * Defaults to 2 decimal places
     *
     * @param     $value
     * @param int $decimals
     * @return null|string
     */
    function format_price_for_paypal($value, $decimals = 2)
    {
        if (in_array(get_system_currency(), config('system.non_decimal_currencies'))) {
            $decimals = 0;
        }

        return number_format($value, $decimals, '.', '');
    }
}

if (!function_exists('get_formated_decimal')) {
    function get_formated_decimal($value = 0, $trim = true, $decimal = 0)
    {
        // if (! $decimal )
        //     $decimal = $decimal == 0 ? 0 : config('system_settings.decimals', 2);

        $decimal_mark = config('system_settings.currency.decimal_mark', '.');

        $value = number_format($value, $decimal, $decimal_mark, config('system_settings.currency.thousands_separator', ','));

        if ($trim) {
            $arr = explode($decimal_mark, $value);
            if (count($arr) == 2) {
                $temp = rtrim($arr[1], '0');
                $value = $temp ? $arr[0] . $decimal_mark . $temp : $arr[0];
            }
        }

        return $value;
    }
}

if (!function_exists('get_formated_price')) {
    function get_formated_price($value = 0, $decimal = null)
    {
        if (in_array(get_system_currency(), config('system.non_decimal_currencies'))) {
            $decimal = 0;
        }

        $price = get_formated_currency($value, $decimal);

        return $price;

        // if ($decimal == 0) {
        //     return $price;
        // }

        // $arr = explode(config('system_settings.currency.decimal_mark', '.'), $price);

        // if (count($arr) == 2) {
        //     return $arr[1] > 0 ? $arr[0] . '<sup class="price-fractional">' . $arr[1] . '</sup>' : $arr[0];
        // }

        // return $price;
    }
}

if (!function_exists('get_formated_currency')) {
    function get_formated_currency($value = 0, $decimal = null)
    {
        if ($decimal && in_array(get_system_currency(), config('system.non_decimal_currencies'))) {
            $decimal = null;
        }

        // If the value is negative
        if ($value < 0) {
            $value = get_formated_decimal($value * -1, $decimal ? false : true, $decimal);

            return '-' . get_currency_prefix() . $value . get_currency_suffix();
        }

        $value = get_formated_decimal($value, $decimal ? false : true, $decimal);

        return get_currency_prefix() . $value . get_currency_suffix();
    }
}

if (!function_exists('get_currency_prefix')) {
    function get_currency_prefix()
    {
        return config('system_settings.currency.symbol_first') ? get_formated_currency_symbol() : '';
    }
}

if (!function_exists('get_currency_suffix')) {
    function get_currency_suffix()
    {
        return config('system_settings.currency.symbol_first') ? '' : get_formated_currency_symbol();
    }
}

if (!function_exists('get_formated_currency_symbol')) {
    function get_formated_currency_symbol()
    {
        if (config('system_settings.show_currency_symbol')) {
            $space = config('system_settings.show_space_after_symbol') ? ' ' : '';

            if (config('system_settings.currency.symbol_first')) {
                return get_currency_symbol() . ($space);
            }

            return ($space) . get_currency_symbol();
        }

        return '';
    }
}

if (!function_exists('get_currency_code')) {
    function get_currency_code()
    {
        return config('system_settings.currency.iso_code') ?? 'USD';
    }
}

if (!function_exists('get_formated_dimension')) {
    function get_formated_dimension($packaging)
    {
        $dimension = get_formated_decimal($packaging->width) . ' x ' . get_formated_decimal($packaging->height);

        if ($packaging->depth && $packaging->depth > 0) {
            $dimension .= ' x ' . get_formated_decimal($packaging->depth);
        }

        return $dimension . ' ' . config('system_settings.length_unit');
    }
}

if (!function_exists('get_formated_weight')) {
    function get_formated_weight($value = 0)
    {
        if ($value == null) {
            return null;
        }

        return get_formated_decimal($value, true, 0) . ' ' . config('system_settings.weight_unit');
    }
}

if (!function_exists('get_formated_order_number')) {
    function get_formated_order_number($shop_id = null, $order_id = null)
    {
        $order_id = $order_id ?? str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

        if ($shop_id == null && Auth::guard('web')->check()) {
            $shop_id = Auth::user()->merchantId();
        }

        return getShopConfig($shop_id, 'order_number_prefix') . $order_id . getShopConfig($shop_id, 'order_number_suffix');
    }
}

// if (! function_exists('php_max_execution_time'))
// {
//     // Returns the time limit in php config file
//     function php_max_execution_time() {
//         $max_execution_time = ini_get('max_execution_time');

//         return $max_execution_time . 's';
//     }
// }

if (!function_exists('file_upload_max_size')) {
    // Returns a file size limit in bytes based on the PHP upload_max_filesize
    // and post_max_size
    function file_upload_max_size()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }

        return format_bytes($max_size);
    }
}

if (!function_exists('parse_size')) {
    function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
}

if (!function_exists('format_bytes')) {
    function format_bytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('generate_ranges')) {
    /**
     * Return array of different ranges
     */
    function generate_ranges($min, $max, $number_of_ranges = 5)
    {
        $range = ($max - $min) / $number_of_ranges;
        $ranges = [];

        for ($i = 0; $i < $number_of_ranges; $i++) {
            $end = intval($min + $range);
            $ranges[$i]['lower'] = $min;
            $ranges[$i]['upper'] = $end;
            $min = $end;
        }

        return $ranges;
    }
}

if (!function_exists('get_percentage_of')) {
    function get_percentage_of($old_num, $new_num)
    {
        return get_formated_decimal((($old_num - $new_num) * 100) / $old_num);
    }
}

if (!function_exists('get_formated_shipping_range_of')) {
    /**
     * get_formated_shipping_range_of given shipping rate
     *
     * @param $tax
     */
    function get_formated_shipping_range_of($rate)
    {
        if (!is_object($rate)) {
            $rate = DB::table('shipping_rates')->find($rate);
        }

        if ($rate->based_on == 'weight') {
            $lower = get_formated_weight($rate->minimum);
            $upper = get_formated_weight($rate->maximum);
        } else {
            $lower = get_formated_currency($rate->minimum);
            $upper = get_formated_currency($rate->maximum);
        }

        if (get_formated_decimal($rate->maximum) > 0) {
            return  $lower . ' - ' . $upper;
        }

        return  trans('app.and_up', ['value' => $lower]);
    }
}

// Shipping zone
if (!function_exists('get_countries_in_shipping_zone')) {
    function get_countries_in_shipping_zone($shipping_zone)
    {
        return Country::select('id', 'iso_code', 'name', 'active')
            ->whereIn('id', $shipping_zone->country_ids)
            ->withCount('states')->with('states:id,country_id')->get();
    }
}

// COUNTRY
if (!function_exists('get_countries_name_with_states')) {
    function get_countries_name_with_states($ids)
    {
        if (is_array($ids)) {
            $countries = DB::table('countries')->select('iso_code', 'name', 'id')->whereIn('id', $ids)->get()->toArray();
            $all_states = DB::table('states')->whereIn('country_id', $ids)->pluck('country_id', 'id')->toArray();

            if (!empty($countries)) {
                $result = [];
                foreach ($countries as $country) {
                    $states = array_filter($all_states, function ($value) use ($country) {
                        return $value == $country->id;
                    });

                    $result[$country->id]['code'] = $country->iso_code;
                    $result[$country->id]['name'] = $country->name;
                    $result[$country->id]['states'] = array_keys($states);
                }

                return $result;
            }
        } else {
            $country_data = DB::table('countries')->select('iso_code', 'name')->find($country);
        }
    }
}

if (!function_exists('get_flag_img_by_code')) {
    function get_flag_img_by_code($code, $plain = false)
    {
        $full_path = sys_image_path('flags') . $code . '.png';

        if (!file_exists($full_path)) {
            $full_path = sys_image_path('flags') . 'default.gif';
        }

        if ($plain) {
            return asset($full_path);
        }

        return '<img src="' . asset($full_path) . '" alt="' . $code . '"/>';
    }
}

if (!function_exists('get_formated_country_name')) {
    function get_formated_country_name($country, $code = null)
    {
        if (is_numeric($country)) {
            $country_data = DB::table('countries')->select('iso_code', 'name')->find($country);
            $country = $country_data->name;
            $code = $country_data->iso_code;
        }

        if ($code) {
            return get_flag_img_by_code($code) . ' <span class="indent5">' . $country . '</span>';
        }

        return $country;
    }
}

if (!function_exists('get_item_details_of')) {
    /**
     * Return the item detiails for the given inventory id
     *
     * @param $tax
     */
    function get_item_details_of($id)
    {
        $item_details = DB::table('inventories')->select([
            'id', 'sku', 'description', 'key_features', 'condition', 'condition_note', 'shipping_weight', 'min_order_quantity', 'available_from',
        ])->where('id', $id)->first();

        return $item_details;
    }
}

if (!function_exists('get_shipping_zone_of')) {
    /**
     * Return the shipping zone id of given shop and country and state
     *
     * @param $tax
     */
    function get_shipping_zone_of($shop, $country, $state = null)
    {
        $cant_ship = new stdClass(); // A blank std class for null

        // When got null on country
        if ($country == null) {
            return $cant_ship;
        }

        // If the iso_2 code given instead of ID as country
        if (!is_numeric($country)) {
            $temp_country = DB::table('countries')->select('id', 'active')
                ->where('iso_code', $country)->first();

            $country = optional($temp_country)->id;
        }

        // If the iso_2 code given instead of ID as state
        if ($state && !is_numeric($state)) {
            $temp_state = DB::table('states')->select('id', 'active')->where([
                ['iso_code', '=', $state],
                ['country_id', '=', $country],
            ])->first();

            $state = optional($temp_state)->id;
        }

        // Check if the marketplace is worldwide_business_area
        if (!config('system_settings.worldwide_business_area')) {
            // Need the country's active value to check the business area
            if (!isset($temp_country)) {
                $temp_country = DB::table('countries')->select('id', 'active')->where([
                    ['id', '=', $country],
                    ['active', '=', 1],
                ])->first();

                // Return back if the area is not in active business area
                if (!$temp_country || $temp_country->active != 1) {
                    return $cant_ship;
                }
            }

            // Need the state's active value to check the business area
            if ($state && !isset($temp_state)) {
                $temp_state = DB::table('states')->select('id', 'active')->where([
                    ['id', '=', $state],
                    ['country_id', '=', $country],
                    ['active', '=', 1],
                ])->first();

                // Return back if the area is not in active business area
                if (!$temp_state || $temp_state->active != 1) {
                    return $cant_ship;
                }
            }
        }

        // Get number of states
        if ($state) {
            $state_counts = get_state_count_of($country);
        }

        $zones = DB::table('shipping_zones')
            ->select(['id', 'name', 'tax_id', 'country_ids', 'state_ids', 'rest_of_the_world'])
            ->where('shop_id', $shop)->where('active', 1)
            ->get();

        foreach ($zones as $zone) {
            // Check the the shop has a worldwide shipping zone
            if ($zone->rest_of_the_world == 1) {
                $worldwide = $zone;
            }

            $countries = unserialize($zone->country_ids);

            // Skip if the country is not found in this zone
            if (empty($countries) || !in_array($country, $countries)) {
                continue;
            }

            // If the country has no state or the state is not given, then return the zone
            if ($state == null || $state_counts == 0) {
                return $zone;
            }

            $states = unserialize($zone->state_ids);

            // Skip if the country has states but the id not supplied
            if ($state_counts > 0 && $state == null) {
                continue;
            }

            if (in_array($state, $states)) {
                return $zone;
            }
        }

        return isset($worldwide) ? $worldwide : $cant_ship;
    }
}

if (!function_exists('get_state_count_of')) {
    /**
     * Return total number of states of given country
     *
     * @param $tax
     */
    function get_state_count_of($country)
    {
        return DB::table('states')->where('country_id', $country)->count();
    }
}

if (!function_exists('get_states_of')) {
    /**
     * Get states ids of given countries.
     *
     * @param  int $countries
     *
     * @return array
     */
    function get_states_of($countries, $all = false)
    {
        $states = DB::table('states');

        if (is_array($countries)) {
            $states->whereIn('country_id', $countries);
        } else {
            $states->where('country_id', $countries);
        }

        if (!$all) {
            $states->where('active', 1);
        }

        return $states->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('get_business_area_of')) {
    /**
     * Get states ids of given countries.
     *
     * @param  int $countries
     *
     * @return array
     */
    function get_business_area_of($countries)
    {
        $states = State::select('id', 'name', 'iso_code', 'active')->orderBy('name', 'asc');

        if (is_array($countries)) {
            $states->whereIn('country_id', $countries);
        } else {
            $states->where('country_id', $countries);
        }

        return $states->get();
    }
}

if (!function_exists('get_id_of_model')) {
    /**
     * Return ID og the given table using where
     *
     * @param  string $table Name of the table
     * @param  string $where Name of the field
     * @param  string $value The value conpire to
     *
     * @return int
     */
    function get_id_of_model($table, $where, $value)
    {
        $temp = DB::table($table)->select('id')->where($where, $value)->first();

        return optional($temp)->id;
    }
}

if (!function_exists('cart_item_count')) {
    /**
     * Get cart item count for customer.
     */
    function cart_item_count($customer_id = null)
    {
        // return Cache::rememberForever('cart_item_count', function() {
        if (!$customer_id) {
            $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
        }

        $cart_list = DB::table('carts')->join('cart_items', 'cart_items.cart_id', '=', 'carts.id')
            ->whereNull('customer_id')->whereNull('deleted_at')->where('ip_address', get_visitor_IP());

        if ($customer_id) {
            $cart_list = $cart_list->orWhere('customer_id', $customer_id);
        }

        return $cart_list->count();
        // });
    }
}

if (!function_exists('getTaxRate')) {
    /**
     * Return taxe rate for the given tax id
     *
     * @param $tax
     */
    function getTaxRate($tax_id = null)
    {
        $tax_id = $tax_id ?? Tax::DEFAULT_TAX_ID;

        $rate = DB::table('taxes')->select('taxrate')->where('id', $tax_id)->first();

        return $rate ? $rate->taxrate : null;
    }
}

if (!function_exists('getShippingRates')) {
    /**
     * Get shipping rates list for the given zone or shop.
     */
    function getShippingRates($zone = null)
    {
        if ($zone) {
            return ShippingRate::where('shipping_zone_id', $zone)
                ->with('carrier:id,name')->orderBy('rate', 'asc')->get();
        }

        // Return empty object if zone it is not given and not an user
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->merchantId()) {
            return new stdClass();
        }

        return DB::table('shipping_zones')
            ->join('shipping_rates', 'shipping_zones.id', 'shipping_rates.shipping_zone_id')
            ->where('shipping_zones.shop_id', Auth::guard('web')->user()->merchantId())
            ->where('shipping_zones.active', 1)
            ->orderBy('shipping_rates.rate', 'asc')
            ->get();
    }
}

if (!function_exists('getTrackingUrl')) {
    /**
     * Return tracking utl for the given carrier and tracking id
     *
     * @param $carrier
     * @param $tracking_id
     */
    function getTrackingUrl($tracking_id = null, $carrier = null)
    {
        if (!$tracking_id || !$carrier) {
            return '#';
        }

        $carrier = DB::table('carriers')->select('tracking_url')
            ->where('id', $carrier)
            ->first();

        if ($carrier) {
            return str_replace('@', $tracking_id, $carrier->tracking_url);
        }

        return '#';
    }
}

if (!function_exists('filterShippingOptions')) {
    /**
     * Return filtered shipping options for a given zone and price
     *
     * @param $shop
     * @param $price
     * @param $weight
     */
    function filterShippingOptions($zone, $price, $weight = null)
    {
        $results = DB::table('shipping_rates')
            ->where('shipping_zone_id', $zone)->orderBy('rate');

        $results->where(function ($query) use ($price, $weight) {
            $query->where('based_on', 'price')
                ->where('minimum', '<=', $price)
                ->where(function ($q) use ($price) {
                    $q->where('maximum', '>=', $price)
                        ->orWhereNull('maximum');
                });

            if ($weight) {
                $query->orWhere(function ($q) use ($weight) {
                    $q->where('based_on', 'weight')
                        ->where('minimum', '<=', $weight)
                        ->where('maximum', '>=', $weight);
                });
            }
        })
            ->select('shipping_rates.*', 'carriers.name as carrier_name')
            ->leftJoin('carriers', 'shipping_rates.carrier_id', '=', 'carriers.id');

        return $results->get();
    }
}

if (!function_exists('getFreeShippingObject')) {
    /**
     * Return free shipping options
     *
     * @param $zone
     */
    function getFreeShippingObject($zone = null)
    {
        return [
            'id' => null,
            'name' => trans('api.free_shipping'),
            'shipping_zone_id' => $zone && !is_numeric($zone) ? $zone->id : $zone,
            'carrier_id' => null,
            'carrier_name' => trans('theme.std_shipping_carrier'),
            'cost' => '$0.00',
            'cost_raw' => 0,
            'rate' => "0.00",
            'delivery_takes' => trans('api.std_delivery_time'),
        ];
    }
}

if (!function_exists('getPlatformDefaultPackaging')) {
    /**
     * Return default packaging ID for given shop
     */
    function getPlatformDefaultPackaging()
    {
        return DB::table('packagings')->select('id', 'name', 'cost')
            ->whereNull('shop_id')->where('id', Packaging::FREE_PACKAGING_ID)->first();
    }
}

if (!function_exists('getDefaultPackaging')) {
    /**
     * Return default packaging ID for given shop
     *
     * @param $int shop
     */
    function getDefaultPackaging($shop = null)
    {
        $shop = $shop ?? Auth::user()->merchantId();

        $packaging = DB::table('packagings')
            ->select('id', 'name', 'cost')
            ->where([
                ['shop_id', '=', $shop],
                ['default', '=', '1'],
                ['active', '=', '1'],
            ])
            ->whereNull('deleted_at')->first();

        if ($packaging) {
            return $packaging;
        }

        return getPlatformDefaultPackaging();
    }
}

if (!function_exists('getPackagings')) {
    /**
     * Return Packaging options for perticulater shop
     *
     * @param $int shop
     */
    function getPackagings($shop = null)
    {
        $shop = $shop ?? Auth::user()->merchantId();

        return DB::table('packagings')->select('id', 'name', 'cost')
            ->where([
                ['shop_id', '=', $shop],
                ['active', '=', '1'],
            ])
            ->whereNull('deleted_at')->get();
    }
}

if (!function_exists('getPackagingCost')) {
    /**
     * Return packaging Cost for the given id
     *
     * @param $int packaging
     */
    function getPackagingCost($packaging = null)
    {
        if (!$packaging) {
            return null;
        }

        $packaging = DB::table('packagings')->select('cost')
            ->where('id', $packaging)
            ->first();

        return $packaging ? $packaging->cost : null;
    }
}

if (!function_exists('getShippingingCost')) {
    /**
     * Return shipping Cost for the given id
     *
     * @param $int shipping
     */
    function getShippingingCost($shipping = null)
    {
        if (!$shipping) {
            return null;
        }

        $shipping_rate = DB::table('shipping_rates')->select('rate')
            ->where('id', $shipping)
            ->first();

        return $shipping_rate ? $shipping_rate->rate : null;
    }
}

if (!function_exists('find_string_in_array')) {
    /**
     * find string or sub_string in array of string
     *
     * @param  array $arr haystack
     * @param  string $string needle
     *
     * @return bool
     */
    function find_string_in_array($arr, $string)
    {
        return array_filter($arr, function ($value) use ($string) {
            return strpos($value, $string) !== false;
        });
    }
}

if (!function_exists('userLevelCompare')) {
    /**
     * Compare two user access level and
     * return true is $user can access the $comparable users information
     *
     * @param  mix $compare
     * @param   $user request user
     *
     * @return bool
     */
    function userLevelCompare($compare, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$compare instanceof User) {
            $compare = User::findorFail($compare);
        }

        // If the comparable user is from a shop and the request user is the owner of the shop
        if (
            $compare->merchantId() && $user->isMerchant() &&
            $user->merchantId() === $compare->merchantId()
        ) {
            return true;
        }

        //Return if the user is from a shop and the compare user is not from the same shop
        if (!$user->isFromPlatform() && $user->merchantId() !== $compare->merchantId()) {
            return false;
        }

        //Return true, If comparable user role level not set
        //and requesr user from platform or same shop
        if (!$compare->role->level) {
            return $user->isFromPlatform() || $user->merchantId() == $compare->merchantId();
        }

        //If the comparable user role have level.
        //Then the request user must have role level set and have to be an user level user
        return $user->role->level && $compare->role->level > $user->role->level;
    }
}

if (!function_exists('get_value_from')) {
    /**
     * Get value from a given table and id
     *
     * @param  int $ids    The primary keys
     * @param  string $table
     * @param  mix $field
     *
     * @return mix
     */
    function get_value_from($ids, $table, $field)
    {
        if (is_array($ids)) {
            $values = DB::table($table)->select($field)->whereIn('id', $ids)->get()->toArray();

            if (!empty($values)) {
                $result = [];
                foreach ($values as $value) {
                    $result[] = $value->$field;
                }

                return $result;
            }
        } else {
            $value = DB::table($table)->select($field)->where('id', $ids)->first();

            if (!empty($value) && isset($value->$field)) {
                return $value->$field;
            }
        }

        return null;
    }
}

if (!function_exists('get_package_options_settings')) {
    function get_package_options_settings($prefix)
    {
        return DB::table('options')->where('option_name', 'like', $prefix . '_%')->get();
    }
}

if (!function_exists('get_from_option_table')) {
    function get_from_option_table($field, $default = null)
    {
        $record = DB::table('options')->select('option_value')
            ->where('option_name', $field)->first();

        if ($record) {
            $value = $record->option_value;

            if (is_serialized($value)) {
                $value = unserialize($value);
                $value = $value ? $value : [];
            }

            return $value;
        }

        // Insert the option when the default value is given
        if ($default !== null) {
            $now = Carbon::now();

            DB::table('options')->insert([
                'option_name' => $field,
                'option_value' => is_array($default) ? serialize($default) : $default,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $default;
    }
}

if (!function_exists('update_option_table_record')) {
    function update_option_table_record($option, $data)
    {
        $data = is_array($data) ? serialize($data) : $data;

        return DB::table('options')->where('option_name', $option)
            ->update([
                'option_value' => $data,
                'updated_at' => Carbon::now(),
            ]);
    }
}

if (!function_exists('update_or_create_option_table_record')) {
    function update_or_create_option_table_record($option, $data)
    {
        // Try to update first
        $update = update_option_table_record($option, $data);

        // When the update failed, create
        if (!$update) {
            $now = Carbon::now();

            $update = DB::table('options')->insert([
                'option_name' => $option,
                'option_value' => is_array($data) ? serialize($data) : $data,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $update;
    }
}

// if (! function_exists('get_from_given_collection')) {
//     function get_from_given_collection(Collection $collection, $field, $value)
//     {
//         return $collection->firstWhere($field, $value);
//     }
// }

if (!function_exists('get_yes_or_no')) {
    /**
     * Return YES or No tring for views base on a given bool value
     *
     * @param  bool $value
     *
     * @return string
     */
    function get_yes_or_no($value = null)
    {
        return $value == 1 ? trans('app.yes') : trans('app.no');
    }
}

if (!function_exists('get_msg_folder_name_from_label')) {
    /**
     * get_msg_folder_name_from_label
     *
     * @param  int $label
     *
     * @return string
     */
    function get_msg_folder_name_from_label($label = 1)
    {
        switch ($label) {
            case Message::LABEL_INBOX:
                return trans('app.message_labels.inbox');
            case Message::LABEL_SENT:
                return trans('app.message_labels.sent');
            case Message::LABEL_DRAFT:
                return trans('app.message_labels.draft');
            case Message::LABEL_SPAM:
                return trans('app.message_labels.spam');
            case Message::LABEL_TRASH:
                return trans('app.message_labels.trash');
            default:
                return trans('app.message_labels.inbox');
        }
    }
}

if (!function_exists('get_payment_method_type')) {
    function get_payment_method_type($type)
    {
        switch ($type) {
            case PaymentMethod::TYPE_PAYPAL:
                return [
                    'name' => trans('app.payment_method_type.paypal.name'),
                    'description' => trans('app.payment_method_type.paypal.description'),
                    'admin_description' => trans('app.payment_method_type.paypal.admin_description'),
                ];

            case PaymentMethod::TYPE_CREDIT_CARD:
                return [
                    'name' => trans('app.payment_method_type.credit_card.name'),
                    'description' => trans('app.payment_method_type.credit_card.description'),
                    'admin_description' => trans('app.payment_method_type.credit_card.admin_description'),
                ];

            case PaymentMethod::TYPE_MANUAL:
                return [
                    'name' => trans('app.payment_method_type.manual.name'),
                    'description' => trans('app.payment_method_type.manual.description'),
                    'admin_description' => trans('app.payment_method_type.manual.admin_description'),
                ];

            case PaymentMethod::TYPE_OTHERS:
                return [
                    'name' => trans('app.payment_method_type.others.name'),
                    'description' => trans('app.payment_method_type.others.description'),
                    'admin_description' => trans('app.payment_method_type.others.admin_description'),
                ];

            default:
                return [
                    'name' => '',
                    'description' => '',
                    'admin_description' => '',
                ];
        }
    }
}

if (!function_exists('get_order_status_name')) {
    /**
     * get_order_status_name
     *
     * @param  int $label
     *
     * @return string
     */
    function get_order_status_name($status = 1)
    {
        switch ($status) {
            case Order::STATUS_WAITING_FOR_PAYMENT:
                return trans('app.statuses.waiting_for_payment');
            case Order::STATUS_PAYMENT_ERROR:
                return trans('app.statuses.payment_error');
            case Order::STATUS_CONFIRMED:
                return trans('app.statuses.confirmed');
            case Order::STATUS_FULFILLED:
                return trans('app.statuses.fulfilled');
            case Order::STATUS_AWAITING_DELIVERY:
                return trans('app.statuses.awaiting_delivery');
            case Order::STATUS_DELIVERED:
                return trans('app.statuses.delivered');
            case Order::STATUS_RETURNED:
                return trans('app.statuses.refunded');
            case Order::STATUS_CANCELED:
                return trans('app.canceled');
            default:
                return '';
        }
    }
}

if (!function_exists('get_payment_status_name')) {
    /**
     * get_payment_status_name
     *
     * @param  int $label
     *
     * @return string
     */
    function get_payment_status_name($status = 1)
    {
        switch ($status) {
            case Order::PAYMENT_STATUS_UNPAID:
                return trans('app.statuses.unpaid');
            case Order::PAYMENT_STATUS_PENDING:
                return trans('app.statuses.pending');
            case Order::PAYMENT_STATUS_PAID:
                return trans('app.statuses.paid');
            case Order::PAYMENT_STATUS_INITIATED_REFUND:
                return trans('app.statuses.refund_initiated');
            case Order::PAYMENT_STATUS_PARTIALLY_REFUNDED:
                return trans('app.statuses.partially_refunded');
            case Order::PAYMENT_STATUS_REFUNDED:
                return trans('app.statuses.refunded');
            default:
                return trans('app.statuses.unpaid');
        }
    }
}

if (!function_exists('get_exception_message')) {
    /**
     * get_payment_status_name
     *
     * @param  int $label
     *
     * @return string
     */
    function get_exception_message($exception)
    {
        return $exception->getMessage() . ' | Line: ' . $exception->getLine() . ' | File: ' . $exception->getFile();
    }
}

if (!function_exists('get_disput_status_name')) {
    /**
     * get_disput_status_name
     *
     * @param  int $label
     *
     * @return string
     */
    function get_disput_status_name($status = 1)
    {
        switch ($status) {
            case Dispute::STATUS_NEW:
                return trans('app.statuses.new');
            case Dispute::STATUS_OPEN:
                return trans('app.statuses.open');
            case Dispute::STATUS_WAITING:
                return trans('app.statuses.waiting');
            case Dispute::STATUS_APPEALED:
                return trans('app.statuses.appealed');
            case Dispute::STATUS_SOLVED:
                return trans('app.statuses.solved');
            case Dispute::STATUS_CLOSED:
                return trans('app.statuses.closed');
                // case Dispute::STATUS_REFUNDED:
                //     return trans('app.statuses.refunded');
            default:
                return '';
        }
    }
}

if (!function_exists('get_chat_status_name')) {
    /**
     * get_chat_status_name
     *
     * @param  int $status
     *
     * @return string
     */
    function get_chat_status_name($status = ChatConversation::STATUS_NEW)
    {
        switch ($status) {
            case ChatConversation::STATUS_NEW:
                return trans('app.statuses.new');
            case ChatConversation::STATUS_READ:
                return trans('app.statuses.read');
            case ChatConversation::STATUS_UNREAD:
                return trans('app.statuses.unread');
        }
    }
}

if (!function_exists('get_cancellation_reason_txt')) {
    /**
     * get_cancellation_reason_txt
     *
     * @param  int $status
     *
     * @return string
     */
    function get_cancellation_reason_txt($status = Cancellation::STATUS_NEW)
    {
        switch ($status) {
            case Cancellation::STATUS_NEW:
                return trans('app.statuses.new');
            case Cancellation::STATUS_OPEN:
                return trans('app.statuses.open');
            case Cancellation::STATUS_APPROVED:
                return trans('app.statuses.approved');
            case Cancellation::STATUS_DECLINED:
                return trans('app.statuses.declined');
        }
    }
}

if (!function_exists('get_activity_title')) {
    function get_activity_title($activity)
    {
        if (!$activity->causer) {
            return trans('app.system') . ' ' . $activity->description . ' ' . trans('app.this') . ' ' . $activity->log_name;
        }

        return Str::title($activity->description) . ' ' . trans('app.by') . ' ' . $activity->causer->getName();
    }
}

if (!function_exists('isActive')) {
    /**
     * Set the active class to the current opened menu.
     *
     * @param  string|array $route
     * @param  string       $className
     * @return string
     */
    function isActive($route, $className = 'active')
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route) ? $className : '';
        }

        if (Route::currentRouteName() == $route) {
            return $className;
        }

        if (strpos(URL::current(), $route)) {
            return $className;
        }

        return '';
    }
}

if (!function_exists('verifyRequiredDataForBulkUpload')) {
    function verifyRequiredDataForBulkUpload($data, $type = 'inventory')
    {
        if (!is_array($data)) {
            $data = unserialize($data);
        }

        $required = array_flip(config('system.import_required.' . $type, []));

        $value = array_intersect_ukey($data, $required, 'checkAllValuesExistInAArray');

        return count($value) == count($required);
    }
}

if (!function_exists('checkAllValuesExistInAArray')) {
    /**
     * check all the Values Exist of $b exist is array $a
     *
     * @param  array $a
     * @param  array $b
     *
     * @return mix
     */
    function checkAllValuesExistInAArray($a, $b)
    {
        if ($a === $b) {
            return 0;
        }

        return ($a > $b) ? 1 : -1;
    }
}

if (!function_exists('is_address_autocomplete_on')) {
    function is_address_autocomplete_on()
    {
        return config('services.google.place_api_key');
    }
}

if (!function_exists('is_chat_enabled')) {
    /**
     * Check if the chat window is enabled for the shop
     */
    function is_chat_enabled(Shop $shop)
    {
        return config('system_settings.enable_chat') && $shop->config->isChatEnabled();
    }
}

if (!function_exists('is_subscription_enabled')) {
    /**
     * Check if the subscription enabled
     */
    function is_subscription_enabled()
    {
        return config('system.subscription.enabled');
    }
}

if (!function_exists('get_subscription_billing')) {
    /**
     * Check if the subscription billing
     */
    function get_subscription_billing()
    {
        return config('system.subscription.billing');
    }
}

if (!function_exists('subscription_billing_type')) {
    /**
     * Get the subscription billing type
     */
    function subscription_billing_type()
    {
        return config('system.subscription.billing');
    }
}

if (!function_exists('is_stripe_configured')) {
    /**
     * Check if the stripe APIs configured
     */
    function is_stripe_configured()
    {
        return config('services.stripe.client_id') && config('services.stripe.key') &&
            config('services.stripe.secret') && config('services.stripe.webhook.secret');
    }
}

if (!function_exists('get_chat_room_name')) {
    /**
     * Return zcart chat_room_name
     */
    function get_chat_room_name($room = '')
    {
        return "zcart-chat{$room}";
    }
}

if (!function_exists('get_vendor_chat_room_id')) {
    /**
     * Return vendor_chat_room_id
     */
    function get_vendor_chat_room_id($shop = null)
    {
        $shop = $shop ?? Auth::user()->merchantId();

        if ($shop instanceof Shop) {
            return $shop->slug;
        }

        return Shop::find($shop)->slug;
    }
}

if (!function_exists('get_private_chat_room_id')) {
    /**
     * Return unique private_chat_room_id
     */
    function get_private_chat_room_id(ChatConversation $coverseation)
    {
        return get_chat_room_name($coverseation->shop_id . $coverseation->customer_id);
    }
}

if (!function_exists('multi_tag_explode')) {
    /**
     * extend php's explode functions
     */
    function multi_tag_explode($delimiters, $string)
    {
        return explode($delimiters[0], str_replace($delimiters, $delimiters[0], $string));
    }
}

if (!function_exists('get_featured_items')) {
    /**
     * Get featured Products
     * @return array
     */
    function get_featured_items()
    {
        return Cache::rememberForever('featured_items', function () {
            $items = get_from_option_table('featured_items', []);

            if (!empty($items)) {
                return Inventory::whereIn('id', $items)
                    ->available()
                    ->with([
                        'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                        'image:path,imageable_id,imageable_type',
                    ])->get();
            }

            return $items;
        });
    }
}

if (!function_exists('get_featured_brands')) {
    /**
     * Get featured brands
     * @return array
     */
    function get_featured_brands()
    {
        if (!$featured_brands = get_from_option_table('featured_brands', [])) {
            return collect([]);
        }

        return Cache::rememberForever('featured_brands', function () use ($featured_brands) {
            return Manufacturer::select('id', 'name', 'slug', 'description')
                ->whereIn('id', $featured_brands)
                ->with('featureImage:path,imageable_id,imageable_type')
                ->get();
        });
    }
}

if (!function_exists('get_featured_category')) {
    /**
     * Get featured category
     * @return array
     */
    function get_featured_category()
    {
        return Cache::rememberForever('featured_categories', function () {
            return Category::select('id', 'name', 'slug')
                ->with('featureImage:path,imageable_id,imageable_type')
                // ->withCount('listings')
                ->orderBy('order', 'asc')
                ->featured()->get();
        });
    }
}

if (!function_exists('get_trending_categories')) {
    /**
     * Get trending_categories
     * @return array
     */
    function get_trending_categories()
    {
        if (!$trending_ids = get_from_option_table('trending_categories', [])) {
            return collect([]);
        }

        return Cache::remember('trending_categories', config('cache.remember.trending_category_items', 0), function () use ($trending_ids) {
            return Category::select('id', 'name', 'slug', 'order')
                ->whereIn('id', $trending_ids)
                ->whereHas('listings')
                ->with([
                    'listings' => function ($q) {
                        $q->select(ListHelper::common_select_attr('inventory'))
                            ->available()
                            ->with([
                                'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                                'image:path,imageable_id,imageable_type'
                            ])
                            ->zipcode()->groupBy('product_id', 'shop_id')
                            ->take(config('system.popular.take.trending', 20))
                            ->get();
                    },
                ])->get();
        });
    }
}

if (!function_exists('get_deal_of_the_day')) {
    /**
     * Get get_deal_of_the_day
     * @return inventory
     */
    function get_deal_of_the_day()
    {
        return Cache::rememberForever('deal_of_the_day', function () {
            return Inventory::where('id', get_from_option_table('deal_of_the_day'))
                ->with([
                    'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                    'images:path,imageable_id,imageable_type'
                ])
                ->available()
                ->first();
        });
    }
}

if (!function_exists('create_file_from_base64')) {
    /**
     * extend php's explode functions
     */
    function create_file_from_base64($base64File)
    {
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));

        // save it to temporary dir first.
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString() . '.png';

        file_put_contents($tmpFilePath, $fileData);

        // this just to help us get file info.
        $tmpFile = new \Symfony\Component\HttpFoundation\File\File($tmpFilePath);

        return new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            false // Mark it as test, since the file isn't from real HTTP POST.
        );
    }
}

// STRIPE Helper
// if (! function_exists('getStripeAuthorizeUrl'))
// {
//     /**
//      * Return authorize_url to Stripe connect authorization
//      */
//     function getStripeAuthorizeUrl()
//     {
//         return "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=" . config('services.stripe.client_id') . "&scope=read_write&state=" . csrf_token();
//     }

/**
 * This method will return unique random number
 *
 * @return code()
 */

if (!function_exists('generateUniqueNumber')) {
    function generateUniqueNumber()
    {
        do {
            $code = random_int(100000, 999999);
        } while (DB::table('password_resets')->where('token', $code)->first());

        return $code;
    }
}

/**
 * Update Env file
 * 
 * @param array $data
 */
if (!function_exists('update_env')) {
    function update_env($data = []): void
    {
        // When the user is admin
        if (Auth::user()->isAdmin()) {
            if (!empty($data)) {
                $env = new \App\Services\EnvManager();
                foreach ($data as $key => $value) {
                    $env->setValue($key, $value, true);
                }
            }
        }

        return;
    }
}
