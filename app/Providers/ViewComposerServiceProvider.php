<?php

namespace App\Providers;

use App\Models\Config;
use App\Models\Ticket;
use App\Models\Inventory;
use App\Models\PaymentMethod;
use App\Models\SystemConfig;
use App\Models\ChatConversation;
use App\Charts\LatestSales;
use App\Charts\SalesByPeriod;
use App\Charts\VisitorsOfMonths;
use App\Helpers\CharttHelper;
use App\Helpers\ListHelper;
use App\Helpers\Statistics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->composeAddressForm();

        $this->composeAdminNavigations();

        $this->composeAttributeForm();

        $this->composeAttributeValueForm();

        $this->composeBillingSection();

        $this->composeCountryForm();

        $this->composeTicketSection();

        $this->composeBlogForm();

        $this->composeBannerForm();

        $this->composeCarrierForm();

        $this->composeCategoryForm();

        $this->composeCategorySubGroupForm();

        $this->composeChatPage();

        $this->composeConfigPage();

        $this->composeCouponForm();

        $this->composeDashboardForAdmin();

        $this->composeDashboardForMerhcant();

        $this->composeGeneralConfigPage();

        $this->composeCreateOrderForm();

        $this->composeDisputeResponseForm();

        $this->composeEmailTemplatePartialForm();

        $this->composeFaqTopicForm();

        $this->composeFaqForm();

        $this->composeFeaturedCategoriesForm();

        $this->composeInventoryForm();

        $this->composeInventoryVariantForm();

        $this->composeManufacturerForm();

        $this->composeMerchantRregistrationForm();

        // $this->composeOrderFulfillmentForm();

        $this->composeOrderUpdateForm();

        $this->composePageForm();

        $this->composePlatformKpi();

        $this->composeMerchantKpi();

        $this->composeProductForm();

        $this->composePromotionOptions();

        $this->composeRefundInitiationForm();

        $this->composeRoleForm();

        $this->composeRoleShow();

        $this->composeSearchFilterForm();

        // $this->composeSearchCustomerForm();

        //        $this->composeSetVariantForm();

        $this->composeShippingRateForm();

        $this->composeShippingZoneForm();

        $this->composeShopForm();

        $this->composeSystemGeneralPage();

        $this->composeSystemConfigPage();

        $this->composeTaxForm();

        $this->composeTicketCreateForm();

        $this->composeTicketStatusForm();

        $this->composeTicketAssignForm();

        $this->composeUserForm();

        $this->composeViewPaymentMethodsPage();

        $this->composeWarehouseForm();
    }

    /**
     * compose partial view of role form
     */
    private function composeAdminNavigations()
    {
        View::composer(

            'admin.header',

            function ($view) {
                // $view->with('active_announcement', get_global_announcement());
                $view->with('unread_messages', ListHelper::unreadMessages());
            }
        );
    }

    /**
     * compose partial view of role form
     */
    private function composeRoleForm()
    {
        View::composer(

            'admin.role._form',

            function ($view) {
                $view->with('modules', ListHelper::modulesWithPermissions());
            }
        );
    }

    /**
     * compose partial view of role
     */
    private function composeRoleShow()
    {
        View::composer(

            'admin.role._show',

            function ($view) {
                $view->with('modules', ListHelper::modulesWithPermissions());
            }
        );
    }

    /**
     * compose partial view of user form
     */
    private function composeUserForm()
    {
        View::composer(

            'admin.user._form',

            function ($view) {
                $view->with('roles', ListHelper::roles());
            }
        );
    }

    /**
     * compose partial view of attribute form
     */
    private function composeAttributeForm()
    {
        View::composer(

            'admin.attribute._form',

            function ($view) {
                $view->with('typeList', ListHelper::attribute_types());
                $view->with('categories', ListHelper::catWithSubGrpListArray());
            }
        );
    }

    /**
     * compose partial view of attribute value form
     */
    private function composeAttributeValueForm()
    {
        View::composer(

            'admin.attribute-value._form',

            function ($view) {
                $view->with('attributeList', ListHelper::attributes());
            }
        );
    }

    /**
     * compose partial view of category form
     */
    private function composeCategoryForm()
    {
        View::composer(

            'admin.category._form',

            function ($view) {
                $view->with('catList', ListHelper::catGrpSubGrpListArray());
                $view->with('attrsList', ListHelper::attributes(true));
            }
        );
    }

    /**
     * compose partial view of CategorySubGroupForm form
     */
    private function composeCategorySubGroupForm()
    {
        View::composer(

            'admin.category._formSubGrp',

            function ($view) {
                $view->with('catGroups', ListHelper::categoryGrps());
            }
        );
    }

    /**
     * compose partial view of shipping rate form
     */
    private function composeShippingRateForm()
    {
        View::composer(

            'admin.shipping_rate._form',

            function ($view) {
                $view->with('carriers', ListHelper::carriers());
            }
        );
    }

    /**
     * compose partial view of shipping zone form
     */
    private function composeShippingZoneForm()
    {
        View::composer(

            'admin.shipping_zone._form',

            function ($view) {
                $view->with('taxes', ListHelper::taxes());
                $view->with('countries', ListHelper::active_bussiness_areas());
            }
        );
    }

    /**
     * compose partial view of shop form
     */
    private function composeShopForm()
    {
        View::composer(

            'admin.shop._edit',

            function ($view) {
                $view->with('timezones', ListHelper::timezones());
            }
        );
    }

    /**
     * compose partial view of country form
     */
    private function composeCountryForm()
    {
        View::composer(

            'admin.country._form',

            function ($view) {
                $view->with('timezones', ListHelper::timezones());
                $view->with('currencies', ListHelper::currencies(true));
            }
        );
    }

    /**
     * compose partial view of product form
     */
    private function composeProductForm()
    {
        View::composer(

            'admin.product._form',

            function ($view) {
                $view->with('categories', ListHelper::catWithSubGrpListArray());

                $view->with('manufacturers', ListHelper::manufacturers());

                $view->with('gtin_types', ListHelper::gtin_types());

                $view->with('countries', ListHelper::countries());

                $view->with('tags', ListHelper::tags());
            }
        );
    }

    /**
     * compose partial view of Promotion index
     */
    private function composePromotionOptions()
    {
        View::composer(

            'admin.promotions.options',

            function ($view) {
                $view->with('tagline', get_from_option_table('promotional_tagline'));

                $view->with('featured_items', get_featured_items());

                $view->with('featured_brands', get_featured_brands());

                $view->with('featured_categories', ListHelper::featured_categories());
            }
        );
    }

    /**
     * compose partial view of genaral inventory form
     */
    private function composeInventoryForm()
    {
        View::composer(

            'admin.inventory._form',

            function ($view) {
                // $view->with('attributes', ListHelper::attributeWithValues());

                $view->with('packagings', ListHelper::packagings());

                $view->with('warehouses', ListHelper::warehouses());

                $view->with('suppliers', ListHelper::suppliers());

                $view->with('inventories', ListHelper::inventories());

                $view->with('tags', ListHelper::tags());
            }
        );
    }

    /**
     * compose partial view of inventory variant form
     */
    private function composeInventoryVariantForm()
    {
        View::composer(

            'admin.inventory._formWithVariant',

            function ($view) {
                $view->with('packagings', ListHelper::packagings());

                $view->with('warehouses', ListHelper::warehouses());

                $view->with('suppliers', ListHelper::suppliers());

                $view->with('inventories', ListHelper::inventories());

                $view->with('tags', ListHelper::tags());
            }
        );
    }

    /**
     * compose partial view of set variant form
     */
    private function composeSetVariantForm()
    {
        View::composer(

            'admin.inventory._set_variant',

            function ($view) {
                $view->with('attributes', ListHelper::attributeWithValues());
            }
        );
    }

    /**
     * compose partial view of tax form
     */
    private function composeTaxForm()
    {
        View::composer(

            'admin.tax._form',

            function ($view) {
                $view_data = $view->getData();

                $country_id = isset($view_data['tax']) ?
                    $view_data['tax']['country_id'] :
                    config('system_settings.address_default_country');

                $view->with('countries', ListHelper::countries());

                $view->with('states', ListHelper::states($country_id));
            }
        );
    }

    /**
     * compose partial view of Email template partial form
     */
    private function composeEmailTemplatePartialForm()
    {
        View::composer(

            'admin.partials._email_template_id_field',

            function ($view) {
                $view->with('email_templates', ListHelper::email_templates());
            }
        );
    }

    /**
     * compose partial view of FAQ Topic template partial form
     */
    private function composeFaqTopicForm()
    {
        View::composer(

            'admin.faq-topic._form',

            function ($view) {
                $view->with('topics', ListHelper::faq_topics_for());
            }
        );
    }

    /**
     * compose partial view of FAQ template partial form
     */
    private function composeFaqForm()
    {
        View::composer(

            'admin.faq._form',

            function ($view) {
                $view->with('topics', ListHelper::faq_topics());
            }
        );
    }

    /**
     * compose partial view of manufacturer form
     */
    private function composeManufacturerForm()
    {
        View::composer(

            'admin.manufacturer._form',

            function ($view) {
                $view->with('countries', ListHelper::countries());
            }
        );
    }

    /**
     * compose partial view of Merchant Rregistration form
     */
    private function composeMerchantRregistrationForm()
    {
        View::composer(

            'auth.register',

            function ($view) {
                $view->with('plans', ListHelper::plans());
            }
        );
    }

    /**
     * compose partial view of Billing form
     */
    private function composeBillingSection()
    {
        View::composer(

            'admin.account._billing',

            function ($view) {
                $view->with('plans', \DB::table('subscription_plans')
                    ->where('deleted_at', null)->orderBy('order', 'asc')
                    ->select('plan_id', 'name', 'cost')->get());

                $view->with('current_plan', Auth::user()->getCurrentPlan());

                $view->with('billable', Auth::user()->shop->hasPaymentMethods() ? Auth::user()->shop : null);

                if (SystemConfig::isPaymentConfigured('stripe')) {
                    $view->with('intent', Auth::user()->shop->createSetupIntent());
                }
            }
        );
    }

    /**
     * compose partial view of Billing form
     */
    private function composeTicketSection()
    {
        View::composer(

            'admin.account._ticket',

            function ($view) {
                $view->with([
                    'tickets' => Ticket::createdByMe()->withCount(['replies', 'attachments'])->orderBy('status', 'asc')->get(),
                ]);
            }
        );
    }

    /**
     * compose partial view of warehouse form
     */
    private function composeWarehouseForm()
    {
        View::composer(

            'admin.warehouse._form',

            function ($view) {
                $view->with('staffs', ListHelper::staffs());
            }
        );
    }

    /**
     * compose partial view of carrier form
     */
    private function composeCarrierForm()
    {
        View::composer(

            'admin.carrier._form',

            function ($view) {
                $view->with('taxes', ListHelper::taxes());
            }
        );
    }

    /**
     * compose partial view of address form
     */
    private function composeAddressForm()
    {
        View::composer(

            'address._form',

            function ($view) {
                $view_data = $view->getData();

                $country_id = isset($view_data['address']) ?
                    $view_data['address']['country_id'] :
                    config('system_settings.address_default_country');

                if (
                    isset($view_data['addressable_type']) && \App\Models\Customer::class == $view_data['addressable_type']
                    ||
                    isset($view_data['address']) && \App\Models\Customer::class == $view_data['address']['addressable_type']
                ) {
                    $view->with('address_types', ListHelper::address_types());
                }

                $view->with('countries', ListHelper::countries());

                $view->with('states', ListHelper::states($country_id));
            }
        );
    }

    /**
     * compose partial view of invoice and order filter
     */
    private function composeSearchFilterForm()
    {
        View::composer(

            'admin.partials._filter',

            function ($view) {
                $view->with('statuses', ListHelper::order_statuses());
                $view->with('payments', ListHelper::payment_statuses());
            }
        );
    }

    /**
     * compose partial view of invoice and order filter
     */
    private function composeCreateOrderForm()
    {
        View::composer(

            'admin.order.create',

            function ($view) {
                $config = Config::findOrFail(auth()->user()->merchantId());

                $view->with('payment_statuses', ListHelper::payment_statuses());
                $view->with('payment_methods', optional($config->paymentMethods)->pluck('name', 'id'));

                $inventories = Inventory::mine()->available()->with('attributeValues')->get();

                foreach ($inventories as $inventory) {
                    $str = ' - ';

                    foreach ($inventory->attributeValues as $k => $attrValue) {
                        $str .= $attrValue->value . ' - ';
                    }

                    $str = substr($str, 0, -3);

                    $items[$inventory->id] = $inventory->sku . ': ' . $inventory->title . $str . ' - ' . $inventory->condition;

                    if ($inventory->image) {
                        $img_path = optional($inventory->image)->path;
                    } elseif ($inventory->product->featuredImage) {
                        $img_path = optional($inventory->product->featuredImage)->path;
                    } else {
                        $img_path = optional($inventory->product->image)->path;
                    }

                    $product_info[$inventory->id] = [
                        'id' => $inventory->product_id,
                        'image' => $img_path,
                        'salePrice' => round($inventory->sale_price, 2),
                        'offerPrice' => round($inventory->offer_price, 2),
                        'stockQtt' => $inventory->stock_quantity,
                        'shipping_weight' => $inventory->shipping_weight,
                        'offerStart' => $inventory->offer_start,
                        'offerEnd' => $inventory->offer_end,
                    ];
                }

                $view->with('products', isset($items) ? $items : []);
                $view->with('inventories', isset($product_info) ? $product_info : []);
            }
        );
    }

    /**
     * compose partial view of order fulfillment
     */
    // private function composeOrderFulfillmentForm()
    // {
    //     View::composer(

    //         'admin.order._fulfill',

    //         function ($view) {
    //             $view->with('carriers', ListHelper::carriers());
    //         }
    //     );
    // }

    /**
     * compose partial view of order status update
     */
    private function composeOrderUpdateForm()
    {
        View::composer(

            'admin.order._edit',

            function ($view) {
                $view->with('order_statuses', ListHelper::order_statuses());
            }
        );
    }

    /**
     * compose partial view of ticket create form
     */
    private function composeTicketCreateForm()
    {
        View::composer(

            'admin.account._create_ticket',

            function ($view) {
                $view->with('ticket_categories', ListHelper::ticket_categories());
                $view->with('priorities', ListHelper::ticket_priorities());
            }
        );
    }

    /**
     * compose partial view of ticket status form
     */
    private function composeTicketStatusForm()
    {
        View::composer(

            'admin.ticket._status_form',

            function ($view) {
                $view->with('priorities', ListHelper::ticket_priorities());
                $view->with('statuses', ListHelper::ticket_statuses_all());
            }
        );
    }

    /**
     * compose partial view of ticket status form
     */
    private function composeTicketAssignForm()
    {
        View::composer(

            'admin.ticket._assign',

            function ($view) {
                $view->with('users', ListHelper::platform_users());
            }
        );
    }

    /**
     * compose partial view of dispute status form
     */
    private function composeDisputeResponseForm()
    {
        View::composer(

            'admin.dispute._response',

            function ($view) {
                $view->with('statuses', ListHelper::dispute_statuses());
            }
        );
    }

    private function composeRefundInitiationForm()
    {
        View::composer(

            'admin.refund._initiate',

            function ($view) {
                $view->with('orders', ListHelper::paid_orders());
                $view->with('statuses', ListHelper::refund_statuses());
            }
        );
    }

    private function composeChatPage()
    {
        View::composer(

            'admin.chat._left_nav',

            function ($view) {
                $view->with('chats', ChatConversation::mine()->get());
            }
        );
    }

    /**
     * compose partial view of Config Page
     */
    private function composeConfigPage()
    {
        View::composer(

            'admin.config.index',

            function ($view) {
                $config = Config::findOrFail(auth()->user()->merchantId());
                $view->with('staffs', ListHelper::staffs());
                $view->with('taxes', ListHelper::taxes());
                $view->with('suppliers', ListHelper::suppliers());
                $view->with('warehouses', ListHelper::warehouses());
                $view->with('packagings', ListHelper::packagings());
                $view->with('payment_methods', optional($config->paymentMethods)->pluck('name', 'id'));
            }
        );
    }

    /**
     * compose Coupon Form
     */
    private function composeCouponForm()
    {
        View::composer(

            'admin.coupon._form',

            function ($view) {
                $view->with([
                    'shipping_zones' => ListHelper::shipping_zones(),
                ]);
            }
        );
    }

    /**
     * compose page create form
     */
    private function composePageForm()
    {
        View::composer(

            'admin.page._form',

            function ($view) {
                $view->with([
                    'positions' => ListHelper::page_positions(),
                ]);
            }
        );
    }

    /**
     * compose KPI report
     */
    private function composePlatformKpi()
    {
        View::composer(

            'admin.report.platform.kpi',

            function ($view) {
                $new_vendor_count = Cache::remember('new_vendor_count', config('cache.remember.todays_stats', 3600), function () {
                    return Statistics::new_vendor_count();
                });

                $view->with([
                    'new_vendor_count' => $new_vendor_count,
                    'monthly_recuring_revenue' => 0,
                    'last_30_days_commission' => 0,
                    // 'chartReferrers' => $chartReferrers,
                    // 'chartVisitorTypes' => $chartVisitorTypes,
                    // 'chartDevices' => $chartDevices,
                ]);
            }
        );
    }

    /**
     * compose KPI report
     */
    private function composeMerchantKpi()
    {
        View::composer(

            'admin.report.merchant.kpi',

            function ($view) {
                // Charts
                $start = CharttHelper::getStartDate();
                $end = $start->copy()->subMonths(config('charts.default.months'))->startOfMonth();

                $chart = new SalesByPeriod($start, $end, 'M');

                $salesData = Statistics::sales_data_by_period($start, $end);

                // Preparing Sales amount dataset
                $salesTotal = CharttHelper::prepareSaleTotal($salesData, 'M');
                foreach ($chart->labels as $key => $label) {
                    $dataset[$key] = array_key_exists($label, $salesTotal) ? round($salesTotal[$label]) : 0;
                }

                $chart->dataset(trans('app.sale'), 'column', $dataset);

                $period = $start->diffInDays($end);

                // Statistics
                $orders_count = Cache::remember('latest_order_count', config('cache.remember.todays_stats', 3600), function () use ($period) {
                    return Statistics::latest_order_count($period);
                });

                $abandoned_carts = Cache::remember('abandoned_carts', config('cache.remember.todays_stats', 3600), function () use ($period) {
                    return Statistics::abandoned_carts_count($period);
                });

                $refund_total = Cache::remember('latest_refund_total', config('cache.remember.todays_stats', 3600), function () use ($period) {
                    return Statistics::latest_refund_total($period);
                });

                $top_listings = Cache::remember('top_listings', config('cache.remember.statistics', 86400), function () {
                    return ListHelper::top_listing_items(null, 10);
                });

                $top_suppliers = Cache::remember('top_suppliers', config('cache.remember.statistics', 86400), function () {
                    return ListHelper::top_suppliers(5);
                });

                $top_categories = Cache::remember('top_categories', config('cache.remember.statistics', 86400), function () {
                    return ListHelper::top_categories(5);
                });

                $top_customers = Cache::remember('top_customers', config('cache.remember.statistics', 86400), function () {
                    return ListHelper::top_customers(10);
                });

                $returning_customers = Cache::remember('returning_customers', config('cache.remember.statistics', 86400), function () {
                    return ListHelper::returning_customers(10);
                });

                $view->with([
                    'chart' => $chart,
                    'top_listings'                 => $top_listings,
                    'top_suppliers'                => $top_suppliers,
                    'top_categories'               => $top_categories,
                    'top_customers'                => $top_customers,
                    'returning_customers'          => $returning_customers,
                    'orders_count'                 => $orders_count,
                    'abandoned_carts_count'        => $abandoned_carts,
                    'latest_refund_total'          => $refund_total,
                    'sales_total'                  => $salesData->sum('total'),
                    'discount_total'               => $salesData->sum('discount'),
                ]);
            }
        );
    }

    /**
     * compose dashboard UI for admin
     */
    private function composeDashboardForAdmin()
    {
        View::composer(

            'admin.dashboard.platform',

            function ($view) {
                // Charts
                $months = config('charts.visitors.months');

                $visitorsData = Cache::remember('visitors_of_month', config('cache.remember.statistics', 86400), function () use ($months) {
                    return CharttHelper::visitorsOfMonths($months);
                });

                $chart = Cache::remember('visitors_chart', config('cache.remember.statistics', 86400), function () use ($months, $visitorsData) {
                    $today = \Carbon\Carbon::today()->startOfMonth();
                    $day_count = $today->diffInDays($today->copy()->subMonths($months));
                    $per_day_visits = round(array_sum($visitorsData['visits']) / $day_count);

                    return new VisitorsOfMonths($months, $per_day_visits);
                });

                $chart->dataset(trans('app.page_views'), 'areaspline', $visitorsData['page_views'])->color(config('charts.visitors.colors.page_views'));

                if (array_key_exists('sessions', $visitorsData)) {
                    $chart->dataset(trans('app.sessions'), 'areaspline', $visitorsData['sessions'])->color(config('charts.visitors.colors.sessions'));
                }

                $chart->dataset(trans('app.unique_visits'), 'areaspline', $visitorsData['visits'])->color(config('charts.visitors.colors.unique_visits'));

                // Merchant Counts
                $merchant_count = Cache::remember('merchant_count', config('cache.remember.statistics', 86400), function () {
                    return Statistics::merchant_count();
                });
                $new_merchant_30days = Cache::remember('new_merchant_30days', config('cache.remember.statistics', 86400), function () {
                    return Statistics::merchant_count(30);
                });
                $top_vendors = Cache::remember('top_vendors', config('cache.remember.statistics', 86400), function () {
                    return ListHelper::top_vendors();
                });

                // Visitor Counts
                $todays_visitor_count = Cache::remember('todays_visitor_count', config('cache.remember.todays_stats', 3600), function () {
                    return Statistics::visitor_count('today');
                });

                $d30_visitor_count = Cache::remember('d30_visitor_count', config('cache.remember.statistics', 86400), function () {
                    return Statistics::visitor_count(30);
                });

                $d60_visitor_count = Cache::remember('d60_visitor_count', config('cache.remember.statistics', 86400), function () {
                    return Statistics::visitor_count(60);
                });

                $view->with([
                    'chart' => $chart,
                    'merchant_count'            => $merchant_count,
                    'new_merchant_last_30_days' => $new_merchant_30days,

                    'customer_count'            => Statistics::customer_count(),
                    'new_customer_last_30_days' => Statistics::customer_count(30),

                    'todays_sale_amount'        => Statistics::todays_sale_amount(),
                    'yesterdays_sale_amount'    => Statistics::yesterdays_sale_amount(),

                    'todays_visitor_count'      => $todays_visitor_count,
                    'last_30days_visitor_count' => $d30_visitor_count,
                    'last_60days_visitor_count' => $d60_visitor_count,

                    'dispute_count'             => Statistics::appealed_dispute_count(),
                    'last_30days_dispute_count' => Statistics::appealed_dispute_count(null, 30),
                    'last_60days_dispute_count' => Statistics::appealed_dispute_count(null, 60),

                    'top_customers'             => ListHelper::top_customers(),
                    'top_vendors'               => $top_vendors,

                    'latest_products'           => ListHelper::latest_products(),
                    'open_tickets'              => ListHelper::open_tickets(),

                    'pending_verifications'     => Statistics::pending_verification_count(),
                    'pending_approvals'         => Statistics::pending_approval_count(),
                ]);
            }
        );
    }

    /**
     * compose dashboard UI for merchant
     */
    private function composeDashboardForMerhcant()
    {
        View::composer(

            'admin.dashboard.merchant',

            function ($view) {
                // Charts
                $days = config('charts.latest_sales.days');
                $salesData = CharttHelper::SalesOfLast($days);

                $chart = new LatestSales($days);
                $chart->dataset(trans('app.sale'), 'column', $salesData)->color(config('charts.latest_sales.color'));

                $dispute_count = Statistics::dispute_count(Auth::user()->merchantId());
                $refund_request_count = Statistics::open_refund_request_count();
                $current_plan = Auth::user()->shop->plan;

                $view->with([
                    'chart' => $chart,
                    'top_listings'              => ListHelper::top_listing_items(),
                    'latest_orders'             => ListHelper::latest_orders(),
                    'latest_stocks'             => ListHelper::latest_stocks(),
                    'low_qtt_stocks'            => ListHelper::low_qtt_stocks(),
                    'unfulfilled_order_count'   => Statistics::unfulfilled_order_count(),
                    'latest_order_count'        => Statistics::latest_order_count($days),
                    'todays_order_count'        => Statistics::todays_order_count(),
                    'stock_out_count'           => Statistics::stock_out_count(),
                    'stock_count'               => Statistics::shop_inventories_count(),
                    'todays_sale_amount'        => Statistics::todays_sale_amount(),
                    'yesterdays_sale_amount'    => Statistics::yesterdays_sale_amount(),
                    'last_sale'                 => Statistics::last_sale(),
                    'latest_refund_total'       => Statistics::latest_refund_total($days),
                    'latest_sale_total'         => array_sum($salesData),
                    'dispute_count'             => $dispute_count,
                    'refund_request_count'      => $refund_request_count,
                    'current_plan'              => $current_plan,
                ]);

                // Disputs and Refunds widget (optional)
                if ($dispute_count > 0 || $refund_request_count > 0) {
                    $view->with([
                        'last_30days_dispute_count'  => Statistics::dispute_count(auth()->user()->merchantId(), 30),
                        'last_60days_dispute_count'  => Statistics::dispute_count(auth()->user()->merchantId(), 60),
                        'last_30days_refund_request_count'  => Statistics::refund_request_count(30),
                        'last_60days_refund_request_count'  => Statistics::refund_request_count(60),
                    ]);
                }

                // Time to upgrade widget (optional)
                if ((bool) config('dashboard.upgrade_plan_notice')) {
                    $view->with([
                        // 'stock_count'           => Statistics::shop_inventories_count(), Already loaded
                        'user_count'                => Statistics::shop_user_count(),
                    ]);
                }
            }
        );
    }

    /**
     * compose partial view of Config Page
     */
    private function composeViewPaymentMethodsPage()
    {
        View::composer(

            [
                'admin.config.payment-method.index',
                'admin.config.payment-method.on_off'
            ],

            function ($view) {
                $view->with('payment_method_types', ListHelper::payment_method_types());
                $view->with('payment_methods', PaymentMethod::where('enabled', 1)->get());
                $view->with('config', Config::findOrFail(Auth::user()->merchantId()));
            }
        );
    }

    /**
     * compose partial view of SystemConfig Page
     */
    private function composeSystemConfigPage()
    {
        View::composer(

            'admin.system.config',

            function ($view) {
                $view->with('countries', ListHelper::countries());
                $view->with('states', ListHelper::states());
                $view->with('payment_method_types', ListHelper::payment_method_types());
                $view->with('payment_methods', PaymentMethod::all());
            }
        );
    }

    /**
     * compose partial view of GeneralConfig Page
     */
    private function composeGeneralConfigPage()
    {
        View::composer(

            'admin.config.general',

            function ($view) {
                $view->with('timezones', ListHelper::timezones());
            }
        );
    }

    /**
     * compose partial view of General System Config Page
     */
    private function composeSystemGeneralPage()
    {
        View::composer(

            'admin.system.general',

            function ($view) {
                $view->with('timezones', ListHelper::timezones());
                $view->with('currencies', ListHelper::currencies());
                $view->with('languages', ListHelper::languages());
                $view->with('business_areas', ListHelper::marketplace_business_area());
            }
        );
    }

    /**
     * compose partial view of banner form
     */
    private function composeBannerForm()
    {
        View::composer(

            'admin.banner._form',

            function ($view) {
                $view->with('groups', ListHelper::banner_groups());
            }
        );
    }

    /**
     * compose partial view of Theme Option
     */
    private function composeFeaturedCategoriesForm()
    {
        View::composer(

            'admin.promotions._edit_featured_categories',

            function ($view) {
                $view->with('categories', ListHelper::categories());
                $view->with('featured_categories', ListHelper::featured_categories()->toArray());
            }
        );
    }

    /**
     * compose partial view of blog form
     */
    private function composeBlogForm()
    {
        View::composer(

            'admin.blog._form',

            function ($view) {
                $view->with('tags', ListHelper::tags());
            }
        );
    }
}
