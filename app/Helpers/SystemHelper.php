<?php

use App\Models\Cart;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Customer;
use App\Models\Packaging;
use App\Models\ShippingRate;
use App\Models\SystemConfig;
use App\Helpers\ListHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Laravel\Cashier\Cashier;
use Carbon\Carbon;

if (!function_exists('getPlatformFeeForOrder')) {
    /**
     * return calculated application fee for the given order value
     */
    function getPlatformFeeForOrder($order)
    {
        if (!$order instanceof Order) {
            $order = Order::findOrFail($order);
        }

        $shop = $order->shop;
        $plan = null;
        $transaction_fee = 0;
        $commission = 0;

        // Return zero is on trial period
        if (is_subscription_enabled()) {
            if ($shop->onTrial()) {
                return 0;
            }

            if ($plan = $shop->plan) {
                $transaction_fee = $plan->transaction_fee;
            }
        }

        // Dynamic commission
        if (is_incevio_package_loaded('dynamicCommission')) {
            // Check if custom commission for the shop
            if ($shop->commission_rate !== null) {
                if ($shop->commission_rate > 0) {
                    $commission = ($shop->commission_rate * $order->total) / 100;
                }

                return $commission;
            }

            // Get the dynamic commission amount
            $dynamicCommissions = get_from_option_table('dynamicCommission_milestones');

            // Sort decs milestones mased on amount
            usort($dynamicCommissions, function ($a, $b) {
                return $b['milestone'] - $a['milestone'];
            });

            // Dynamic commission calculation via milestone amount:
            if ($dynamicCommissions) {
                // Get total sold amount
                $sold_amount = $shop->periodic_sold_amount;

                foreach ($dynamicCommissions as $commission) {
                    if ($sold_amount >= $commission['milestone']) {
                        $commission = ($commission['commission'] * $order->total) / 100;

                        return $commission;
                    }
                }
            }
        }

        // Get commissions from the subscription plan
        if ($plan && $plan->marketplace_commission > 0) {
            $commission = ($plan->marketplace_commission * $order->total) / 100;
        }

        return $commission + $transaction_fee;
    }
}

if (!function_exists('setSystemConfig')) {
    /**
     * Set system settings into the config
     */
    function setSystemConfig($shop = null)
    {
        if (!config('system_settings')) {
            // $system_settings = ListHelper::system_settings();
            $system_settings = Cache::rememberForever('system_settings', function () {
                return ListHelper::system_settings();
            });

            config()->set('system_settings', $system_settings);

            // set_time_limit(300); // Set the max_execution_time to 5mins

            setSystemLocale();

            setSystemCurrency();

            setSystemTimezone($shop);
        }

        if ($shop && !config('shop_settings')) {
            setShopConfig($shop);
        }
    }
}

if (!function_exists('setSystemLocale')) {
    /**
     * Set system locale into the config
     */
    function setSystemLocale()
    {
        // Set the default_language
        app()->setLocale(config('system_settings.default_language'));

        // $active_locales = ListHelper::availableLocales();
        $active_locales = Cache::rememberForever('active_locales', function () {
            return ListHelper::availableLocales();
        });

        config()->set('active_locales', $active_locales);
    }
}

if (!function_exists('setSystemTimezone')) {
    /**
     * Set system timezone into the config
     */
    function setSystemTimezone($shop = null)
    {
        // $system_timezone = ListHelper::system_timezone();
        $system_timezone = Cache::rememberForever('system_timezone', function () {
            return ListHelper::system_timezone();
        });

        Config::set('app.timezone', $system_timezone->utc);

        date_default_timezone_set($system_timezone->utc);
    }
}

if (!function_exists('convertFromUTC')) {
    /**
     * @param int $timestamp
     * @param string $timezone
     *
     * @return Carbon
     */
    function convertFromUTC($timestamp, $timezone = null)
    {
        return Carbon::parse($timestamp)->timezone(config('app.timezone', 'UTC'));
    }
}

if (!function_exists('setSystemCurrency')) {
    /**
     * Set system currency into the config
     */
    function setSystemCurrency()
    {
        // $currency = DB::table('currencies')->where('id', config('system_settings.currency_id'))->first();
        $currency = Cache::rememberForever('system_currency', function () {
            return DB::table('currencies')->where('id', config('system_settings.currency_id'))->first();
        });

        // Set Cashier Currency
        // Cashier::useCurrency($currency->iso_code, $currency->symbol);

        if (!$currency) {
            $currency = DB::table('currencies')->where('iso_code', config('cashier.currency'))->first();
        }

        config([
            'cashier.currency' => $currency->iso_code,
            'system_settings.currency' => [
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'iso_code' => $currency->iso_code,
                'symbol_first' => $currency->symbol_first,
                'decimal_mark' => $currency->decimal_mark,
                'thousands_separator' => $currency->thousands_separator,
                'subunit' => $currency->subunit,
            ],
        ]);
    }
}

if (!function_exists('setDashboardConfig')) {
    /**
     * Set dashboard settings into the config
     */
    function setDashboardConfig($dash = null)
    {
        // Unset unwanted values
        unset($dash['user_id'], $dash['created_at']);

        config()->set('dashboard', $dash);
    }
}

if (!function_exists('setShopConfig')) {
    /**
     * Set shop settings into the config
     */
    function setShopConfig($shop = null)
    {
        if (!config('shop_settings')) {
            $shop_settings = ListHelper::shop_settings($shop);

            config()->set('shop_settings', $shop_settings);
        }
    }
}

if (!function_exists('getShopConfig')) {
    /**
     * Return config value for the given shop and column
     *
     * @param $int packaging
     */
    function getShopConfig($shop, $column)
    {
        if (config('shop_settings') && array_key_exists($column, config('shop_settings'))) {
            return config('shop_settings.' . $column);
        }

        return DB::table('configs')->where('shop_id', $shop)->value($column);
    }
}

if (!function_exists('getMysqliConnection')) {
    /**
     * Return Mysqli connection object
     */
    function getMysqliConnection()
    {
        return mysqli_connect(config('database.connections.mysql.host', '127.0.0.1'), config('database.connections.mysql.username', 'root'), config('database.connections.mysql.password'), config('database.connections.mysql.database'), config('database.connections.mysql.port', '3306'));
    }
}

if (!function_exists('setAdditionalCartInfo')) {
    /**
     * Push some extra information into the request
     *
     * @param $request
     */
    function setAdditionalCartInfo($request)
    {
        $total = 0;
        $grand_total = 0;
        $shipping_weight = 0;
        $handling = config('shop_settings.order_handling_cost');

        foreach ($request->input('cart') as $cart) {
            $total = $total + ($cart['quantity'] * $cart['unit_price']);
            $shipping_weight += $cart['shipping_weight'];
        }

        $grand_total = ($total + $handling + $request->input('shipping') + $request->input('packaging') + $request->input('taxes')) - $request->input('discount');

        $request->merge([
            'shop_id' => $request->user()->merchantId(),
            'shipping_weight' => $shipping_weight,
            'item_count' => count($request->input('cart')),
            'quantity' => array_sum(array_column($request->input('cart'), 'quantity')),
            'total' => $total,
            'handling' => $handling,
            'grand_total' => $grand_total,
            'billing_address' => $request->input('same_as_shipping_address') ?
                $request->input('shipping_address') : $request->input('billing_address'),
            'approved' => 1,
        ]);

        return $request;
    }
}

if (!function_exists('prepareFilteredListings')) {
    /**
     * Prepare Result result for front end
     *
     * @param  Request $request
     * @param  collection $items
     *
     * @return collection
     */
    function prepareFilteredListingsNew($request, $catSubGroup)
    {
        $t_listings = [];
        foreach ($catSubGroup as $t_cat) {
            foreach ($t_cat->listings as $item) {
                $t_listings[] = $item;
            }
        }

        return collect($t_listings)->flatten();
    }

    function prepareFilteredListings($request, $categoryGroup)
    {
        $t_listings = [];
        foreach ($categoryGroup->categories as $t_category) {
            $t_products = $t_category->listings()
                ->available()->filter($request->all())
                ->withCount([
                    'orders' => function ($query) {
                        $time = Carbon::now()->subHours(config('system.popular.hot_item.period', 24));

                        $query->withArchived()
                            ->where('order_items.created_at', '>=', $time);
                    }
                ])
                ->with([
                    'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                    // 'feedbacks:rating,feedbackable_id,feedbackable_type',
                    'images:path,imageable_id,imageable_type',
                ])->get();

            foreach ($t_products as $t_product) {
                $t_listings[] = $t_product;
            }
        }

        return collect($t_listings);
    }
}

if (!function_exists('crosscheckCartOwnership')) {
    /**
     * Crosscheck the cart ownership
     *
     * @param \App\Models\Cart $cart
     */
    function crosscheckCartOwnership($request, Cart $cart)
    {
        $bool = $cart->customer_id == null && $cart->ip_address == get_visitor_IP();

        if (Auth::guard('customer')->check()) {
            return $bool || ($cart->customer_id == Auth::guard('customer')->user()->id);
        } elseif (Auth::guard('api')->check()) {
            return $bool || ($cart->customer_id == Auth::guard('api')->user()->id);
        } elseif ($request->customer_id) {
            return $bool || ($cart->customer_id == $request->customer_id);
        }

        return $bool;
    }
}

if (!function_exists('crosscheckAndUpdateOldCartInfo')) {
    /**
     * Crosscheck old cart info with current listing and update
     *
     * @param \App\Models\Cart $cart
     */
    function crosscheckAndUpdateOldCartInfo($request, Cart $cart)
    {
        // If the reqest has nothing to update
        if (empty($request->all())) {
            return $cart;
        }

        // Set customer_id if not set yet
        if (!$cart->customer_id) {
            if (Auth::guard('customer')->check()) {
                $cart->customer_id = Auth::guard('customer')->user()->id;
            } elseif (Auth::guard('api')->check()) {
                $cart->customer_id = Auth::guard('api')->user()->id;
            }
        }

        $total = 0;
        $quantity = 0;
        $shipping_weight = 0;

        // Qtt and Total
        foreach ($cart->inventories as $item) {
            $temp_qtt = $request->quantity ? $request->quantity[$item->id] : $item->pivot->quantity;
            $unit_price = $item->current_sale_price();
            $temp_total = $unit_price * $temp_qtt;

            $shipping_weight += $item->shipping_weight * $temp_qtt;
            $quantity += $temp_qtt;
            $total += $temp_total;

            // Update the cart item pivot table
            $cart->inventories()->updateExistingPivot($item->id, ['quantity' => $temp_qtt, 'unit_price' => $unit_price]);
        }

        // Set qtt and total
        $cart->shipping_weight = $shipping_weight;
        $cart->quantity = $quantity;
        $cart->total = $total;
        // $cart->handling = 0;

        // Set shipping zone
        if ($request->zone_id && $request->zone_id != $cart->shipping_zone_id) {
            $cart->shipping_zone_id = $request->zone_id;
        }

        // Set taxerate
        if ($request->taxrate != $cart->taxrate) {
            $cart->taxrate = getTaxRate($request->tax_id);
        }

        // Shipping
        if ($request->shipping_rate_id) {
            $shippingRate = ShippingRate::select('id', 'rate')->where([
                ['id', '=', $request->shipping_rate_id],
                ['shipping_zone_id', '=', $cart->shipping_zone_id],
            ])->first();

            // abort_unless($shippingRate, 403, trans('theme.notify.seller_doesnt_ship'));

            if ($shippingRate) {
                $cart->shipping_rate_id = $shippingRate->id;
                $cart->shipping = $shippingRate->rate;

                // if ($cart->handling == 0) {
                // $cart->handling = getShopConfig($cart->shop_id, 'order_handling_cost');
                // }
            } elseif ($cart->is_free_shipping()) {
                $cart->shipping_rate_id = null;
                $cart->shipping = 0;
                // $cart->handling = 0;
            }
        }

        // Packaging
        if ($request->packaging_id != $cart->packaging_id) {
            if ($request->packaging_id == Packaging::FREE_PACKAGING_ID) {
                $cart->packaging = null;
                $cart->packaging_id = null;
            } else {
                $packagingCost = Packaging::select('id', 'cost')->where([
                    ['id', '=', $request->packaging_id],
                    ['shop_id', '=', $cart->shop_id],
                ])->active()->first();

                if ($packagingCost) {
                    $cart->packaging = $packagingCost->cost;
                    $cart->packaging_id = $packagingCost->id;
                }
            }
        }

        // if ($request->payment_method) {
        //     $code = $request->payment_method == 'saved_card' ? 'stripe' : $request->payment_method;
        //     $cart->payment_method_id = get_id_of_model('payment_methods', 'code', $code);
        // }

        if ($request->ship_to_country_id) {
            $cart->ship_to_country_id = $request->ship_to_country_id;
        }

        if ($request->ship_to_state_id) {
            $cart->ship_to_state_id = $request->ship_to_state_id;
        }

        $cart->ship_to = $request->ship_to ?? $request->country_id ?? $cart->ship_to;
        $cart->handling = $cart->get_handling_cost();
        $cart->taxes = $cart->get_tax_amount();
        $cart->discount = $cart->get_discounted_amount();
        $cart->grand_total = $cart->calculate_grand_total();
        $cart->save();

        return $cart;
    }
}

if (!function_exists('generate_combinations')) {
    /**
     * Generate all the possible combinations among a set of nested arrays.
     *
     * @param  array   $data  The entrypoint array container.
     * @param  array   &$all  The final container (used internally).
     * @param  array   $group The sub container (used internally).
     * @param  int     $k     The actual key for value to append (used internally).
     * @param  string  $value The value to append (used internally).
     * @param  int $i     The key index (used internally).
     * @param  int     $key   The kay of parent array (used internally).
     * @return array          The result array with all posible combinations.
     */
    function generate_combinations(array $data, array &$all = [], array $group = [], $k = null, $value = null, $i = 0, $key = null)
    {
        $keys = array_keys($data);

        if ((isset($value) === true) && (isset($k) === true)) {
            $group[$key][$k] = $value;
        }

        if ($i >= count($data)) {
            array_push($all, $group);
        } else {
            $currentKey = $keys[$i];

            $currentElement = $data[$currentKey];

            if (count($currentElement) <= 0) {
                generate_combinations($data, $all, $group, null, null, $i + 1, $currentKey);
            } else {
                foreach ($currentElement as $k => $val) {
                    generate_combinations($data, $all, $group, $k, $val, $i + 1, $currentKey);
                }
            }
        }

        return $all;
    }
}

if (!function_exists('updateOptionTable')) {
    /**
     * Update Option table data
     */
    function updateOptionTable(Request $request)
    {
        foreach ($request->except('_token') as $field => $value) {
            $value = is_array($value) ? serialize($value) : $value;

            DB::table('options')->where('option_name', $field)->update([
                'option_value' => $value,
            ]);
        }

        return true;
    }
}

if (!function_exists('get_payment_config_info')) {
    function get_payment_config_info($code, $shop = null)
    {
        if ($shop && !$shop instanceof Shop) {
            $shop = Shop::findOrFail($shop);
        }

        // Return null if the given payment is not configured
        if (!$shop && !SystemConfig::isPaymentConfigured($code)) {
            return null;
        }

        switch ($code) {
            case 'stripe':
                if ($shop) {
                    $config = $shop->config->stripe ?? null;
                } else {
                    $config = config('services.stripe');
                }

                return [
                    'config' => $config,
                    'msg' => trans('theme.notify.we_dont_save_card_info'),
                ];

            case 'instamojo':
                if ($shop) {
                    $config = $shop->config->instamojo ?? null;
                } else {
                    $config = config('services.instamojo');
                }

                return [
                    'config' => $config,
                    'msg' => trans('theme.notify.you_will_be_redirected_to_instamojo'),
                ];

            case 'authorize-net':
                if ($shop) {
                    $config = $shop->config->authorizeNet ?? null;
                } else {
                    $config = config('services.authorize-net');
                }

                return [
                    'config' => $config,
                    'msg' => trans('theme.notify.we_dont_save_card_info'),
                ];

            case 'cybersource':
                if ($shop) {
                    $config = $shop->config->cybersource ?? null;
                } else {
                    $config = config('services.cybersource');
                }

                return [
                    'config' => $config,
                    'msg' => trans('theme.notify.we_dont_save_card_info'),
                ];

            case 'paypal-express':
                if ($shop) {
                    $config = $shop->config->paypalExpress ?? null;
                } else {
                    $config = config('services.paypal');
                }

                return [
                    'config' => $config,
                    'msg' => trans('theme.notify.you_will_be_redirected_to_paypal'),
                ];

            case 'paypal-marketplace':
                if ($shop) {
                    $config = $shop->config->paypalMarketplace ?? null;
                } else {
                    $config = config('paypalMarketplace.api');
                }

                return [
                    'config' => $config,
                    'msg' => trans('theme.notify.you_will_be_redirected_to_paypal'),
                ];

            case 'paystack':
                if ($shop) {
                    $config = $shop->config->paystack ?? null;
                } else {
                    $config = config('services.paystack');
                }

                return [
                    'config' => $config,
                    'msg' => trans('theme.notify.you_will_be_redirected_to_paystack'),
                ];

            case 'razorpay':
                if ($shop) {
                    $config = $shop->config->razorpay ?? null;
                } else {
                    $config = config('razorpay.merchant');
                }

                return [
                    'config' => $config,
                    'msg' => trans('razorpay::lang.pay_with_razorpay'),
                ];

            case 'jrfpay':
                if ($shop) {
                    $config = $shop->config->jrfpay ?? null;
                } else {
                    $config = config('jrfpay.merchant');
                }

                return [
                    'config' => $config,
                    'msg' => trans('jrfpay::lang.pay_with_jrfpay'),
                ];

            case 'mpesa':
                if ($shop) {
                    $config = $shop->config->mpesa ?? null;
                } else {
                    $config = config('mpesa.api');
                }

                return [
                    'config' => $config,
                    'msg' => trans('mpesa::lang.pay_with_mpesa'),
                ];

            case 'zcart-wallet':
                $config = false;

                if ((bool) get_from_option_table('wallet_checkout')) {
                    if (Auth::guard('customer')->check()) {
                        $customer = Auth::guard('customer')->user();
                    } elseif (Auth::guard('api')->check()) {
                        $customer = Auth::guard('api')->user();
                    }

                    $config = isset($customer->wallet) ? $customer->wallet->balance : false;
                }

                return [
                    'config' => $config,
                    'msg' => trans('wallet::lang.pay_by_wallet'),
                ];

            case 'wire':
            case 'cod':
                if ($shop) {
                    $activeManualPaymentMethods = $shop->config->manualPaymentMethods;
                    $config = in_array($code, $activeManualPaymentMethods->pluck('code')->toArray());
                    $info = $activeManualPaymentMethods->where('code', $code)->first();

                    return [
                        'config' => $info ? $info->pivot : null,
                        'msg' => $info ? $info->pivot->additional_details : '',
                    ];
                } else {
                    $info = get_from_option_table('wallet_payment_info_' . $code);

                    return [
                        'config' => $info ? ['additional_details' => $info] : null,
                        'msg' => $info,
                    ];
                }
        }

        return null;
    }
}

if (!function_exists('is_incevio_package_loaded')) {
    function is_incevio_package_loaded($packages)
    {
        $allpackages = is_array($packages) ? $packages : [$packages];

        foreach ($allpackages as $key => $package) {
            $className = Str::studly($package);
            $path = "Incevio\Package\\" . $className . '\\' . $className . 'ServiceProvider';

            // Check if the package file exist
            if (!class_exists($path)) {
                return false;
            }

            // Retrive the package and set to cache
            $registered = Cache::rememberForever(
                'package.' . $package,
                function () use ($package) {
                    return DB::table('packages')->where('slug', $package)->first() ?? false;
                }
            );

            // If class exist then check if the package is active
            if ($registered && $registered->active) {
                continue;
            }

            return false;
        }

        return true;
    }
}

if (!function_exists('can_set_cancellation_fee')) {
    function can_set_cancellation_fee()
    {
        return !vendor_get_paid_directly() && is_incevio_package_loaded(['wallet']);
    }
}

if (!function_exists('vendor_get_paid_directly')) {
    function vendor_get_paid_directly()
    {
        return config('system.order.vendor_get_paid') == 'directly';
    }
}

if (!function_exists('vendor_can_on_off_payment_method')) {
    function vendor_can_on_off_payment_method()
    {
        return config('system.order.vendor_can_on_off_payment_method');
    }
}

if (!function_exists('cancellation_require_admin_approval')) {
    function cancellation_require_admin_approval()
    {
        return config('system_settings.vendor_order_cancellation_fee') == null;
    }
}

if (!function_exists('customer_has_wallet')) {
    function customer_has_wallet()
    {
        return config('system.customer.has_wallet') && is_incevio_package_loaded(['wallet']);
    }
}

if (!function_exists('is_wallet_configured_for')) {
    function is_wallet_configured_for($for = 'customer')
    {
        if ($for == 'vendor' || $for == 'shop') {
            return method_exists(Shop::class, 'getBalanceAttribute');
        }

        return customer_has_wallet() && method_exists(Customer::class, 'getBalanceAttribute');
    }
}

if (!function_exists('get_activity_str')) {
    function get_activity_str($model, $attribute, $new, $old)
    {
        // \Log::info($attribute);
        switch ($attribute) {
            case 'trial_ends_at':
                return trans('app.activities.trial_started');
                break;

            case 'current_billing_plan':
                // $plan = \App\SubscriptionPlan::find([$old, $new])->pluck('name', 'plan_id');

                if (is_null($old)) {
                    return trans('app.activities.subscribed', ['plan' => $new]);
                }

                return trans('app.activities.subscription_changed', ['from' => $old, 'to' => $new]);
                break;

            case 'card_last_four':
                if (is_null($old)) {
                    return trans('app.activities.billing_info_added', ['by' => $new]);
                }

                return trans('app.activities.billing_info_changed', ['from' => $old, 'to' => $new]);
                break;

            case 'order_status_id':
                $attribute = trans('app.status');
                $old = get_order_status_name($old);
                $new = get_order_status_name($new);
                break;

            case 'payment_status':
                $attribute = trans('app.payment_status');
                $old = get_payment_status_name($old);
                $new = get_payment_status_name($new);
                break;

            case 'carrier_id':
                $attribute = trans('app.shipping_carrier');

                if (is_null($old)) {
                    $carrier = \App\Models\Carrier::find($new)->pluck('name', 'id');
                } else {
                    $carrier = \App\Models\Carrier::find([$old, $new])->pluck('name', 'id');
                    $old = $carrier[$old];
                }
                $new = $carrier[$new];
                break;

            case 'tracking_id':
                $attribute = trans('app.tracking_id');
                break;

            case 'timezone_id':
                $attribute = trans('app.timezone');
                $old = get_value_from($old, 'timezones', 'value');
                $new = get_value_from($new, 'timezones', 'value');
                break;

            case 'status':
                $attribute = trans('app.status');
                if (class_basename($model) == 'Dispute') {
                    $old = get_disput_status_name($old);
                    $new = get_disput_status_name($new);
                }
                break;

            case 'active':
                $attribute = trans('app.status');
                $old = $new ? trans('app.inactive') : trans('app.active');
                $new = $new ? trans('app.active') : trans('app.inactive');
                break;

            default:
                $attribute = Str::title(str_replace('_', ' ', $attribute));
                break;
        }

        if ($old) {
            return trans('app.activities.updated', ['key' => $attribute, 'from' => $old, 'to' => $new]);
        }

        return trans('app.activities.added', ['key' => $attribute, 'value' => $new]);
    }
}

if (!function_exists('get_visitor_IP')) {
    /**
     * Get the real IP address from visitors proxy. e.g. Cloudflare
     *
     * @return string IP
     */
    function get_visitor_IP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        // Sometimes the `HTTP_CLIENT_IP` can be used by proxy servers
        $ip = @$_SERVER['HTTP_CLIENT_IP'];
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        // Sometimes the `HTTP_X_FORWARDED_FOR` can contain more than IPs
        $forward_ips = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        if ($forward_ips) {
            $all_ips = explode(',', $forward_ips);

            foreach ($all_ips as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}

if (!function_exists('check_internet_connection')) {
    /**
     * Check Internet Connection Status.
     *
     * @param            string $sCheckHost Default: www.google.com
     * @return           bool
     */
    function check_internet_connection($sCheckHost = 'www.google.com')
    {
        return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 5);
    }
}
