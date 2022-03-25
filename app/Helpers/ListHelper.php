<?php

namespace App\Helpers;

use App\Models\Manufacturer;
use App\Models\Attribute;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\CategorySubGroup;
use App\Models\Customer;
use App\Models\Dispute;
use App\Models\Inventory;
use App\Models\Language;
use App\Models\Message;
use App\Models\Module;
use App\Models\Order;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Refund;
use App\Models\Role;
use App\Models\Shop;
use App\Models\Supplier;
use App\Models\System;
use App\Models\Ticket;
use App\Models\User;
use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

/**
 * This is a helper class to process,upload and remove images from different models
 */
class ListHelper
{
    public static function common_select_attr($model = 'inventory')
    {
        switch ($model) {
            case 'inventory':
                return ['inventories.id', 'inventories.shop_id', 'title', 'inventories.product_id', 'sku', 'condition', 'stock_quantity', 'min_order_quantity', 'available_from', 'sale_price', 'offer_price', 'offer_start', 'offer_end', 'free_shipping', 'slug', 'stuff_pick'];
        }

        return '*';
    }

    /**
     * Get payment_method_types list for form dropdown.
     *
     * @return array
     */
    public static function payment_method_types()
    {
        return  [
            PaymentMethod::TYPE_PAYPAL      => trans('app.payment_method_type.paypal.name'),
            PaymentMethod::TYPE_CREDIT_CARD => trans('app.payment_method_type.credit_card.name'),
            PaymentMethod::TYPE_MANUAL      => trans('app.payment_method_type.manual.name'),
            PaymentMethod::TYPE_OTHERS      => trans('app.payment_method_type.others.name'),
        ];
    }

    public static function payment_statuses()
    {
        return  [
            Order::PAYMENT_STATUS_UNPAID    => trans('app.statuses.unpaid'),
            Order::PAYMENT_STATUS_PENDING   => trans('app.statuses.pending'),
            Order::PAYMENT_STATUS_PAID      => trans('app.statuses.paid'),
        ];
    }

    public static function order_statuses()
    {
        return  [
            Order::STATUS_WAITING_FOR_PAYMENT   =>  trans('app.statuses.waiting_for_payment'),
            Order::STATUS_PAYMENT_ERROR         =>  trans('app.statuses.payment_error'),
            Order::STATUS_CONFIRMED             =>  trans('app.statuses.confirmed'),
            // Order::STATUS_FULFILLED             =>  trans("app.statuses.fulfilled"),
            Order::STATUS_AWAITING_DELIVERY     =>  trans('app.statuses.awaiting_delivery'),
            Order::STATUS_DELIVERED             =>  trans('app.statuses.delivered'),
            Order::STATUS_RETURNED              =>  trans('app.statuses.refunded'),
        ];
    }

    public static function ticket_priorities()
    {
        return  [
            Ticket::PRIORITY_LOW      => trans('app.priorities.low'),
            Ticket::PRIORITY_NORMAL   => trans('app.priorities.normal'),
            Ticket::PRIORITY_HIGH     => trans('app.priorities.high'),
            Ticket::PRIORITY_CRITICAL => trans('app.priorities.critical'),
        ];
    }

    public static function ticket_statuses_new()
    {
        return  [
            Ticket::STATUS_NEW      => trans('app.statuses.new'),
            Ticket::STATUS_OPEN     => trans('app.statuses.open'),
            Ticket::STATUS_PENDING  => trans('app.statuses.pending'),
        ];
    }

    public static function ticket_statuses_all()
    {
        return  self::ticket_statuses_new() + [
            Ticket::STATUS_CLOSED   => trans('app.statuses.closed'),
            Ticket::STATUS_SOLVED   => trans('app.statuses.solved'),
            Ticket::STATUS_SPAM     => trans('app.statuses.spam'),
        ];
    }

    /**
     * Get dispute statuses list for form dropdown.
     *
     * @return array
     */
    public static function dispute_statuses()
    {
        if (Auth::user() instanceof Customer) {
            $statuses = [
                Dispute::STATUS_OPEN     => trans('app.statuses.open'),
                Dispute::STATUS_SOLVED   => trans('app.statuses.solved'),
            ];
        } else {
            $statuses = [
                Dispute::STATUS_NEW      => trans('app.statuses.new'),
                Dispute::STATUS_OPEN     => trans('app.statuses.open'),
                Dispute::STATUS_WAITING  => trans('app.statuses.waiting'),
                Dispute::STATUS_SOLVED   => trans('app.statuses.solved'),
                Dispute::STATUS_CLOSED   => trans('app.statuses.closed'),
            ];
        }

        if (!Auth::user() instanceof Customer && auth()->user()->isFromPlatform()) {
            $statuses[Dispute::STATUS_APPEALED] = trans('app.statuses.appealed');
        }

        return $statuses;
    }

    /**
     * Get refund statuses list for form dropdown.
     *
     * @return array
     */
    public static function refund_statuses()
    {
        return  [
            Refund::STATUS_NEW      => trans('app.statuses.new'),
            Refund::STATUS_APPROVED  => trans('app.statuses.approved'),
            Refund::STATUS_DECLINED => trans('app.statuses.declined'),
        ];
    }

    public static function marketplace_business_area()
    {
        return  [
            '1' => trans('app.worldwide'),
            '0' => trans('app.active_business_area'),
        ];
    }

    public static function faq_topics_for()
    {
        return  [
            'Merchant'    => trans('app.merchants'),
            'Customer'    => trans('app.customers'),
        ];
    }

    /**
     * Get page positions list for form dropdown.
     *
     * @return array
     */
    public static function page_positions()
    {
        return  [
            'copyright_area'    => trans('app.copyright_area'),
            'footer_1st_column' => trans('app.footer_1st_column'),
            'footer_2nd_column' => trans('app.footer_2nd_column'),
            'footer_3rd_column' => trans('app.footer_3rd_column'),
            'main_nav'           => trans('app.main_nav'),
        ];
    }

    /**
     * Get system settings.
     *
     * @return array
     */
    public static function system_settings()
    {
        return System::orderBy('id', 'asc')->first()->toArray();
        // return (array) DB::table('systems')->orderBy('id', 'asc')->first();
    }

    /**
     * Get system timezone.
     *
     * @return array
     */
    public static function system_timezone()
    {
        if (config('system_settings.timezone_id')) {
            return DB::table('timezones')->where('id', config('system_settings.timezone_id'))->first();
        }

        return null;
    }

    /**
     * Get setting list for the shop.
     *
     * @return array
     */
    public static function shop_settings($shop = null)
    {
        $shop = $shop ?? Auth::user()->merchantId(); //Get current user's shop_id
        $settings = DB::table('configs')->where('shop_id', $shop)->first();

        $result = [];
        if ($settings) {
            foreach ($settings as $key => $value) {
                if (is_serialized($value)) {
                    $result[$key] = unserialize($value);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    public static function ticket_categories()
    {
        return DB::table('ticket_categories')->orderBy('name', 'asc')->pluck('name', 'id');
    }

    public static function plans()
    {
        $plans = DB::table('subscription_plans')
            ->where('deleted_at', Null)
            ->orderBy('order', 'asc')
            ->select('plan_id', 'name', 'cost')
            ->get();

        $result = [];
        foreach ($plans as $plan) {
            $result[$plan->plan_id] = $plan->name . ' (' . get_formated_currency($plan->cost, 2) . trans('app.per_month') . ')';
        }

        return $result;
    }

    /**
     * Get unread Messages.
     *
     * @return array
     */
    public static function unreadMessages()
    {
        if (Auth::user()->merchantId()) {
            return Auth::user()->shop->messages()->labelOf(Message::LABEL_INBOX)->unread()->get();
        }

        return Auth::user()->messages()->labelOf(Message::LABEL_INBOX)->unread()->get();

        // return Messages::orderBy('created_at', 'desc')->get();
    }

    /**
     * Get role list for form dropdown.
     * If the logged in user from a shop then show return roles thats are public.
     * otherwise return roles thats not public
     *
     * @return array
     */
    public static function roles()
    {
        $roles = Role::lowerPrivileged();

        if (Auth::user()->isFromPlatform()) {
            $roles->whereNull('shop_id')->notPublic();
        } else {
            $roles->orWhere(
                function ($query) {
                    $query->whereNull('shop_id')->where('public', 1);
                }
            );
        }

        return $roles->where('id', '!=', Role::MERCHANT)->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get list of all available languages formated for dropdown list
     */
    public static function availableLocales()
    {
        return Language::orderBy('order', 'asc')->active()->get();
    }

    /**
     * Get list of all available categories formated for theme
     *
     * @return array
     */
    public static function categoriesForTheme($all = false)
    {
        return Cache::remember('all_categories', config('cache.remember.categories', 0), function () use ($all) {
            $result = CategoryGroup::select('id', 'name', 'slug', 'icon')
                ->with([
                    'logoImage:id,path,imageable_id,imageable_type',
                    'backgroundImage:id,path,imageable_id,imageable_type',
                    'subGroups' => function ($query) use ($all) {
                        $query->select('id', 'slug', 'category_group_id', 'name');

                        if (!$all) {
                            $query->active()->has('categories.products.inventories');
                        }

                        $query->orderBy('categories_count', 'desc')->withCount('categories');
                    },
                    'subGroups.categories' => function ($q) use ($all) {
                        $q->select('id', 'category_sub_group_id', 'name', 'slug', 'description');

                        if (!$all) {
                            $q->active()->has('products.inventories');
                        }
                    },
                ]);

            if (!$all) {
                $result->has('subGroups.categories.products.inventories')->active();
            }

            return $result->orderBy('order', 'asc')->get();
        });
    }

    /**
     * Get list of all available category group
     *
     * @return array
     */
    public static function categoryGrps()
    {
        return DB::table('category_groups')->where('deleted_at', null)->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get list of category sub-group
     *
     * @return array
     */
    public static function catSubGrps()
    {
        return CategorySubGroup::orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get list of category sub-group under the given category
     *
     * @return array
     */
    public static function thisCatSubGrps($category)
    {
        return DB::table('category_sub_groups')->where('deleted_at', null)
            ->where('category_group_id', $category)->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get categories list for form dropdown.
     *
     * @return array
     */
    public static function categories()
    {
        return Category::orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get search_categories list for form dropdown.
     *
     * @return array
     */
    public static function search_categories()
    {
        return Cache::remember('search_category_list', config('cache.remember.categories', 0), function () {
            return CategoryGroup::select('id', 'name', 'slug')
                ->with(['subGroups' => function ($q) {
                    $q->select('name', 'slug', 'category_group_id')->active();
                }])
                ->whereHas('subGroups', function ($q) {
                    $q->active();
                })
                ->orderBy('order', 'desc')
                ->active()->get();
        });
    }

    /**
     * Get all catGrpSubGrpListArray
     *
     * @return array
     */
    public static function catGrpSubGrpListArray()
    {
        $grps = [];
        foreach (self::categoryGrps() as $key => $value) {
            $list = [];

            foreach (self::thisCatSubGrps($key) as $key2 => $value2) {
                $list[$key2] = $value2;
            }

            if (count($list)) {
                $grps[$value] = $list;
            }
        }

        return $grps;
    }

    /**
     * Get all catWithSubGrpList
     *
     * @return array
     */
    public static function catWithSubGrpListArray()
    {
        $categoryGroups = CategoryGroup::select(['id', 'name'])->active()
            ->orderBy('name', 'asc')
            ->with([
                'subGroups' => function ($q) {
                    $q->select(['id', 'name', 'category_group_id'])->orderBy('name', 'asc')->active();
                },
                'subGroups.categories' => function ($q) {
                    $q->select(['id', 'category_sub_group_id', 'name'])->active();
                },
            ])->get();

        $grps = [];
        foreach ($categoryGroups as $categoryGroup) {
            foreach ($categoryGroup->subGroups as $categorySubGroup) {
                $list = [];

                foreach ($categorySubGroup->categories as $category) {
                    $list[$category->id] = $category->name;
                }

                if (count($list)) {
                    $grps[$categoryGroup->name . ' &#9656; ' . $categorySubGroup->name . ' &#9662;'] = $list;
                }
            }
        }

        return $grps;
    }

    /**
     * Get permissions list for form dropdown.
     *
     * @return array
     */
    public static function permissions()
    {
        return Permission::orderBy('module_id', 'asc')->pluck('name', 'id');
    }

    /**
     * Get modulesWithPermissions list.
     *
     * @return array
     */
    public static function modulesWithPermissions()
    {
        return Module::active()->with('permissions')->orderBy('name', 'asc')->get();
    }

    /**
     * Get array of slugsWithModulAccess list.
     *
     * @return array
     */
    public static function slugsWithModulAccess()
    {
        return Permission::with('module')->get()->pluck('module.access', 'slug')->toArray();
    }

    /**
     * Get Popular_blogs list.
     *
     * @return array
     */
    public static function popularBlogs()
    {
        return Blog::select(['id', 'title', 'slug', 'excerpt', 'published_at'])->popular()->take(5)->get();
    }

    /**
     * Get latest_blogs list.
     *
     * @return array
     */
    public static function recentBlogs()
    {
        return Blog::select(['id', 'title', 'slug', 'excerpt', 'published_at'])
            ->published()->recent()->take(5)->get();
    }

    /**
     * Get users list for form dropdown.
     *
     * @return array
     */
    public static function users()
    {
        return DB::table('users')->where('deleted_at', null)->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get users list for form dropdown.
     *
     * @return array
     */
    public static function platform_users()
    {
        return DB::table('users')->where('shop_id', null)->where('role_id', '!=', 3)
            ->where('deleted_at', null)->orderBy('name', 'asc')->pluck('name', 'id');
    }

    public static function shipping_zones($shop = null)
    {
        $shop = $shop ?? Auth::user()->merchantId(); //Get current user's shop_id

        return DB::table('shipping_zones')
            ->where('shop_id', $shop)
            ->where('active', BaseModel::ACTIVE)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get permission slugs for the user role.
     *
     * @return array
     */
    public static function authorizations(User $user = null)
    {
        $user = $user ?? Auth::guard('web')->user(); //Get current user

        if ($user->isSuperAdmin()) {
            return [];
        }

        return $user->role->permissions()->pluck('slug')->toArray();
    }

    /**
     * Get all FAQ topic list for form dropdown.
     *
     * @return array
     */
    public static function faq_topics()
    {
        return DB::table('faq_topics')->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * [open_tickets description]
     *
     * @return [type] [description]
     */
    public static function open_tickets()
    {
        return Ticket::open()->orderBy('priority', 'desc')->with('category')
            ->withCount('replies')->latest()->limit(10)->get();
    }

    /**
     * [top_customers description]
     *
     * @return [type] [description]
     */
    public static function top_customers($limit = 5)
    {
        incevioAutoloadHelpers(getMysqliConnection());
        return Customer::select('id', 'nice_name', 'name', 'email')
            ->with('image:path,imageable_id,imageable_type')
            ->whereHas('orders', function ($query) {
                $query->select('customer_id', 'shop_id', 'total')->withArchived();
                if (Auth::user()->merchantId()) {
                    $query->mine();
                }
            })
            ->withCount(['orders' => function ($q) {
                $q->withArchived();
                if (Auth::user()->merchantId()) {
                    $q->mine();
                }
            }])
            ->orderBy('orders_count', 'desc')
            ->limit($limit)->get();
    }

    /**
     * [returning_customers description]
     *
     * @return [type] [description]
     */
    public static function returning_customers($limit = 5)
    {
        $customers = static::top_customers($limit);

        // Return customer has more than one orders
        return $customers->filter(function ($customer, $key) {
            return $customer->orders->count() > 1;
        });
    }

    /**
     * [top_vendors description]
     *
     * @return [type] [description]
     */
    public static function top_vendors($limit = 5)
    {
        return Shop::select('id', 'owner_id', 'name', 'slug')
            ->with('logoImage:path,imageable_id,imageable_type', 'revenue')
            ->withCount('inventories')
            ->active()
            ->take($limit)
            ->get()
            ->sortByDesc('revenue');
    }

    /**
     * Return unique brand names from the given linstings
     *
     * @return array
     */
    public static function get_unique_brand_names_from_linstings($listings)
    {
        return $listings->pluck('brand')->unique();
    }

    /**
     * Return minimum and maximum price from the given linstings
     *
     * @return array
     */
    public static function get_price_ranges_from_linstings($listings)
    {
        $prices = $listings->pluck('sale_price');

        $priceRange['min'] = floor($prices->min());
        $priceRange['max'] = ceil($prices->max());

        return $priceRange;
    }

    /**
     * Get all merchants list for form dropdown.
     *
     * @return array
     */
    public static function merchants()
    {
        return DB::table('users')->where('role_id', 3)->where('deleted_at', null)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get new merchants list for form dropdown.
     *
     * @return array
     */
    public static function new_merchants()
    {
        return DB::table('users')->whereNull('shop_id')->whereNull('deleted_at')
            ->where('role_id', Role::MERCHANT)->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get users list under the shop for form dropdown.
     *
     * @return array
     */
    public static function staffs($shop = null)
    {
        $shop = $shop ?? Auth::user()->merchantId(); //Get current user's shop_id

        return DB::table('users')->where('shop_id', $shop)->where('deleted_at', null)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get suppliers list for form dropdown.
     *
     * @return array
     */
    public static function suppliers()
    {
        return DB::table('suppliers')->where('shop_id', Auth::user()->merchantId())
            ->where('deleted_at', null)
            ->where('active', BaseModel::ACTIVE)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get timezones list for form dropdown.
     *
     * @return array
     */
    public static function timezones()
    {
        return DB::table('timezones')->pluck('text', 'id');
    }

    public static function languages()
    {
        return DB::table('languages')->where('deleted_at', null)
            ->where('active', BaseModel::ACTIVE)
            ->orderBy('order', 'asc')->pluck('language', 'code');
    }

    /**
     * Get warehouses list for form dropdown.
     *
     * @return array
     */
    public static function warehouses()
    {
        return DB::table('warehouses')
            ->where('shop_id', Auth::user()->merchantId())
            ->where('deleted_at', null)
            ->where('active', BaseModel::ACTIVE)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get carriers list for form dropdown.
     *
     * @return array
     */
    public static function carriers($shop_id = null)
    {
        $shop_id = $shop_id ?? Auth::user()->merchantId();

        return DB::table('carriers')->where('shop_id', $shop_id)
            ->where('deleted_at', null)
            ->where('active', BaseModel::ACTIVE)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get taxes list for form dropdown.
     *
     * @return array
     */
    public static function taxes()
    {
        return DB::table('taxes')->where('active', BaseModel::ACTIVE)
            ->where('deleted_at', null)
            ->where(function ($query) {
                $query->where('public', 1)
                    ->orWhere('shop_id', Auth::user()->merchantId());
            })
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get customers list for form dropdown.
     * @return array
     */
    public static function customers()
    {
        return DB::table('customers')->where('deleted_at', null)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get inventories list for form dropdown.
     * @return array
     */
    public static function inventories($shop = null)
    {
        if (!$shop) {
            $shop = Auth::user()->merchantId();
        }

        return DB::table('inventories')->where('shop_id', $shop)->where('deleted_at', null)
            ->groupBy('product_id', 'shop_id')->orderBy('title', 'asc')->pluck('title', 'id');
    }

    /**
     * Get top listing_items list for merchnat.
     * @return array
     */
    public static function top_listing_items($shop = null, $count = 5)
    {
        if (Auth::user()->isFromPlatform() && $shop) {
            $items = Inventory::where('inventories.shop_id', $shop);
        } else {
            $items = Inventory::where('inventories.shop_id', Auth::user()->merchantId());
        }

        return $items->with('image:path,imageable_id,imageable_type', 'attributeValues:id,value')
            ->select(
                'inventories.id',
                'inventories.shop_id',
                'inventories.sku',
                'products.name',
                'inventories.product_id',
                DB::raw('SUM(order_items.quantity) as sold_qtt'),
                DB::raw('SUM(order_items.unit_price) as gross_sales')
            )
            ->join('products', 'inventories.product_id', 'products.id')
            ->join('order_items', 'inventories.id', 'order_items.inventory_id')
            ->groupBy('inventory_id')
            ->orderBy('sold_qtt', 'desc')
            ->limit($count)->get();
    }

    /**
     * Get top categories list for merchnat.
     * @return array
     */
    public static function top_categories($count = 5)
    {
        return Category::select('id', 'slug', 'name', 'active')
            ->whereHas('listings', function ($query) {
                $query->mine();
            })
            ->withCount('listings')
            ->orderBy('listings_count', 'desc')
            ->limit($count)->get();
    }

    /**
     * Get top suppliers list for merchnat.
     * @return array
     */
    public static function top_suppliers($count = 5)
    {
        return Supplier::select('id', 'shop_id', 'name', 'active')->mine()
            ->with('image:path,imageable_id,imageable_type')
            ->withCount('inventories')
            ->orderBy('inventories_count', 'desc')->limit($count)->get();
    }

    /**
     * Get trendiing items list. Get the most ordered item in given days
     * @return array
     */
    public static function popular_items($days = 7, $limit = 15)
    {
        $from = Carbon::today()->subDays($days)->startOfDay();

        return Inventory::available()
            ->select(static::common_select_attr('inventory'))
            ->withCount([
                'orders' => function ($q) use ($from) {
                    $q->withArchived()->where('orders.created_at', '>', $from);
                },
            ])
            ->with([
                'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                'image:path,imageable_id,imageable_type',
                // 'product:id,slug',
                // 'product.image:path,imageable_id,imageable_type',
            ])
            ->zipcode()
            ->groupBy('product_id', 'shop_id')
            ->orderBy('orders_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /***
     * Original Popular Items
     */
    /* public static function popular_items($days = 7, $count = 15)
    {
        $from = Carbon::today()->subDays($days)->startOfDay();

        return Inventory::available()->withCount(['orders' => function($q) use ($from) {
            $q->withArchived()->where('orders.created_at', '>', $from);
        }])
        ->orderBy('orders_count', 'desc')
        ->with([
            'feedbacks:rating,feedbackable_id,feedbackable_type',
            'image:path,imageable_id,imageable_type',
            'product:id,slug',
            'product.image:path,imageable_id,imageable_type'
        ])
        ->groupBy('product_id', 'shop_id')
        ->limit($count)->get();
    }*/

    /**
     * Get featured products list for dropdown
     * @return array
     */
    public static function featured_items()
    {
        if ($items = get_from_option_table('featured_items', [])) {
            return DB::table('inventories')->whereIn('id', $items)
                ->orderBy('title', 'asc')->pluck('title', 'id')->toArray();
        }

        return null;
    }

    /**
     * Get featured brands list for dropdown
     * @return array
     */
    public static function featured_brands()
    {
        if ($brands = get_from_option_table('featured_brands', [])) {
            return DB::table('manufacturers')->whereIn('id', $brands)
                ->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        }

        return [];
    }

    /**
     * Get Featured Brands
     * @return array
     */
    public static function get_featured_brands()
    {
        if ($featured_brands = get_from_option_table('featured_brands', [])) {
            return Manufacturer::whereIn('id', $featured_brands)->get();
        }

        return [];
    }

    /**
     * Get BBest Finds under Defined Price
     * @return array
     */
    public static function best_find_under($price, $limit = 20)
    {
        if (!$price) {
            return collect([]);
        }

        return Inventory::available()->orderBy('orders_count', 'desc')
            ->select(static::common_select_attr('inventory'))
            ->withCount([
                'orders' => function ($q) {
                    $q->withArchived();
                },
                // 'feedbacks as ratings' => function($q2) {
                //     $q2->select(DB::raw('avg(rating)'));
                // }
            ])
            ->with([
                'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                'image:path,imageable_id,imageable_type',
                // 'product:id,slug',
                // 'product.image:path,imageable_id,imageable_type'
            ])
            ->zipcode()
            ->where(function ($q) use ($price) {
                $q->where('sale_price', '<=', $price)
                    ->orWhere(function ($q) use ($price) {
                        $q->hasOffer()->where('offer_price', '<=', $price);
                    });
            })
            ->groupBy('product_id', 'shop_id')
            ->take($limit)->get();
    }

    /**
     * Get latest_products list
     *
     * @return array
     */
    public static function latest_products()
    {
        return Product::with('featureImage')->latest()->limit(10)->get();
    }

    /**
     * Get latest products that has live listing
     * @return array
     */
    public static function latest_available_items($limit = 10)
    {
        return Cache::remember('latest_items', config('cache.remember.latest_items', 0), function () use ($limit) {
            return Inventory::available()
                ->select(static::common_select_attr('inventory'))
                ->with([
                    'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                    'image:path,imageable_id,imageable_type',
                    // 'product:id,slug',
                    // 'product.image:path,imageable_id,imageable_type'
                ])
                ->zipcode()->groupBy('product_id', 'shop_id')
                ->latest()->limit($limit)->get();
        });
    }

    /**
     * Get variants of product of given item
     * @return array
     */
    public static function variants_of_product($item, $shop = null)
    {
        $variants = Inventory::available()
            ->select(static::common_select_attr('inventory'))
            ->where('product_id', $item->product_id);

        if ($shop) {
            $variants = $variants->where('shop_id', $shop);
        }

        return $variants->with([
            'images:path,imageable_id,imageable_type',
            'attributeValues:id,value,color',
        ])->get();
    }

    /**
     * Get related products of given item
     * @return array
     */
    public static function related_products($item, $limit = 10)
    {
        $catIds = $item->product->categories->pluck('id');

        $productIDs = DB::table('category_product')
            ->whereIn('category_id', $catIds)
            ->pluck('product_id')->toArray();

        if (empty($productIDs)) {
            return collect([]);
        }

        return Inventory::whereIn('product_id', $productIDs)->available()
            ->select(static::common_select_attr('inventory'))
            // ->withCount([
            //     'feedbacks as ratings' => function($q2) {
            //         $q2->select(DB::raw('avg(rating)'));
            //     }
            // ])
            ->with([
                'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                'image:path,imageable_id,imageable_type',
                // 'product:id,slug',
                // 'product.image:path,imageable_id,imageable_type'
            ])
            ->inRandomOrder()
            ->groupBy('product_id', 'shop_id')
            ->limit($limit)->get();
    }

    /**
     * Get linked items of given item
     * @return array
     */
    public static function linked_items($item)
    {
        $linked_items = unserialize($item->linked_items);

        if (empty($linked_items)) {
            return collect([]);
        }

        return Inventory::whereIn('id', $linked_items)->available()
            ->select(static::common_select_attr('inventory'))
            // ->withCount([
            //     'feedbacks as ratings' => function($q2) {
            //         $q2->select(DB::raw('avg(rating)'));
            //     }
            // ])
            ->with([
                'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                'image:path,imageable_id,imageable_type',
                // 'product:id,slug',
                // 'product.image:path,imageable_id,imageable_type'
            ])
            ->groupBy('product_id', 'shop_id')
            ->get();
    }

    /**
     * Get given number of random products
     * @return array
     */
    public static function random_items($limit = null)
    {
        return Cache::remember('random_items', config('cache.remember.random_items', 0), function () use ($limit) {
            $items = Inventory::available()
                ->select(static::common_select_attr('inventory'))
                ->with([
                    'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                    'image:path,imageable_id,imageable_type',
                    // 'product:id,slug',
                    // 'product.image:path,imageable_id,imageable_type'
                ])
                ->zipcode()->groupBy('product_id', 'shop_id')
                ->inRandomOrder('id');

            if ($limit) {
                return $items->limit($limit)->get();
            }

            return $items->simplePaginate(config('mobile_app.view_listing_per_page', 8));
        });
    }

    public static function recentlyViewedItems()
    {
        return Cache::rememberForever('recently_viewed_items', function () {
            $products = Session::get('products.recently_viewed_items');

            if (!$products) {
                return collect([]);
            }

            return Inventory::whereIn('id', $products)->available()
                ->select(static::common_select_attr('inventory'))
                ->with([
                    'image:path,imageable_id,imageable_type',
                    // 'product:id,slug',
                    // 'product.image:path,imageable_id,imageable_type'
                ])
                ->groupBy('product_id', 'shop_id')->get();
        });
    }

    /**
     * Get orders list for form dropdown.
     * @return array
     */
    public static function orders()
    {
        return Order::mine()->orderBy('order_number', 'asc')
            ->pluck('order_number', 'id')->toArray();
    }

    /**
     * Get latest_orders list for merchnat.
     *
     * @return array
     */
    public static function latest_orders()
    {
        return Order::mine()->with('customer')->latest()->limit(10)->get();
    }

    /**
     * Get paid_orders list for form dropdown.
     *
     * @return array
     */
    public static function paid_orders()
    {
        $query = DB::table('orders')->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->where('deleted_at', null);

        if (!Auth::user()->isFromPlatform()) {
            $query = $query->where('shop_id', Auth::user()->merchantId());
        }

        return $query->orderBy('order_number', 'asc')
            ->pluck('order_number', 'id')->toArray();
    }

    /**
     * Get latest_stocks list for merchnat.
     *
     * @return array
     */
    public static function latest_stocks()
    {
        return Inventory::mine()->with('product', 'image:path,imageable_id,imageable_type')
            ->latest()->limit(10)->get();
    }

    /**
     * Get low_qtt_stocks list for merchnat.
     *
     * @return array
     */
    public static function low_qtt_stocks()
    {
        return Inventory::mine()->lowQtt()
            ->with('product', 'image:path,imageable_id,imageable_type')
            ->latest()->limit(10)->get();
    }

    /**
     * Get address_types list for form dropdown.
     *
     * @return array
     */
    public static function address_types()
    {
        return DB::table('address_types')->orderBy('id', 'asc')->pluck('type', 'type');
    }

    /**
     * Get packagings list for form dropdown.
     *
     * @return array
     */
    public static function packagings()
    {
        return DB::table('packagings')
            ->where('shop_id', Auth::user()->merchantId())
            ->where('active', BaseModel::ACTIVE)
            ->where('deleted_at', null)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get country list for form dropdown.
     *
     * @return array
     */
    public static function active_bussiness_areas()
    {
        $countries = DB::table('countries');

        if (!config('system_settings.worldwide_business_area')) {
            $countries->where('active', BaseModel::ACTIVE);
        }

        return $countries->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get country list for form dropdown.
     *
     * @return array
     */
    public static function countries()
    {
        return DB::table('countries')->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get states list for form dropdown.
     *
     * @param  int $country_id
     *
     * @return array
     */
    public static function states($country_id = null)
    {
        $country_id = $country_id ?? config('system_settings.address_default_country');

        return DB::table('states')->where('country_id', $country_id)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get currency list for form dropdown.
     *
     * @return array
     */
    public static function currencies($all = false)
    {
        $query = DB::table('currencies')->select('name', 'symbol', 'iso_code', 'id');

        if (!$all) {
            $query->where('active', BaseModel::ACTIVE);
        }

        $currencies = $query->orderBy('priority', 'asc')->orderBy('name', 'asc')->get();

        $result = [];
        foreach ($currencies as $currency) {
            $result[$currency->id] = $currency->name . ' (' . $currency->iso_code . ' ' . $currency->symbol . ')';
        }

        return $result;
    }

    /**
     * Get attributes list for form dropdown.
     *
     * @return array
     */
    public static function attributes($all = false)
    {
        $query = DB::table('attributes')->where('deleted_at', null);

        if (!$all) {
            $query->where('shop_id', Auth::user()->merchantId());
        }

        return $query->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get attributes list with all values for form dropdown.
     *
     * @return array
     */
    public static function attributeWithValues()
    {
        return Attribute::where('deleted_at', null)
            ->with('attributeValues')->orderBy('order', 'asc')->get();
    }

    /**
     * Get attribute_types list for form dropdown.
     *
     * @return array
     */
    public static function attribute_types()
    {
        return DB::table('attribute_types')->orderBy('type', 'asc')->pluck('type', 'id');
    }

    /**
     * Get manufacturers list for form dropdown.
     *
     * @return array
     */
    public static function manufacturers()
    {
        return DB::table('manufacturers')->where('deleted_at', null)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get gtin_types list for form dropdown.
     *
     * @return array
     */
    public static function gtin_types()
    {
        return DB::table('gtin_types')->pluck('name', 'name');
    }

    /**
     * Get EmailTemplate list with all values for form dropdown.
     *
     * @return array
     */
    public static function email_templates()
    {
        return DB::table('email_templates')->where('deleted_at', null)
            ->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get banner_groups list for form dropdown.
     *
     * @return array
     */
    public static function banner_groups()
    {
        return DB::table('banner_groups')->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get featured_categories list for form dropdown.
     *
     * @return array
     */
    public static function featured_categories()
    {
        return DB::table('categories')->whereNull('deleted_at')
            ->where('featured', true)->pluck('name', 'id');
    }

    /**
     * Get featured_categories list for theme.
     */
    // public static function hot_categories()
    // {
    //     return DB::table('categories')->select('id','name','slug')
    //     ->whereNull('deleted_at')->whereNotNull('featured')->get();
    // }

    /**
     * Get pages list for theme.
     */
    public static function pages($visibility = null)
    {
        return Cache::rememberForever('cached_pages', function () use ($visibility) {
            if ($visibility) {
                return Page::select('title', 'slug', 'position')
                    ->published()->visibilityOf($visibility)->get();
            }

            return Page::select('title', 'slug', 'position')->published()->get();
        });
    }

    /**
     * Get shops list for form dropdown.
     *
     * @return array
     */
    public static function shops()
    {
        return Shop::approved()->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }

    /**
     * Get cart_list list for customer.
     */
    // public static function cart_list()
    // {
    //     $customer = Auth::guard('customer')->user();
    //     // $customer->load('carts.inventories');
    //     // $carts = $customer->carts->inventories;

    //     $cart_list = DB::table('carts')->join('cart_items', 'cart_items.cart_id', '=', 'carts.id')
    //     ->leftJoin('images', function ($join) {
    //         $join->on('images.imageable_id', '=', 'cart_items.inventory_id')->where('images.imageable_type', '=', 'App\Models\Inventory');
    //     })
    //     ->select('cart_items.inventory_id as product_id','cart_items.item_description as product_name','cart_items.quantity as product_quantity','cart_items.unit_price as product_price','cart_items.inventory_id as unique_key','images.path as product_image')
    //     ->where('carts.customer_id', $customer->id)->whereNull('carts.deleted_at')->get();

    //     \Log::info($cart_list->toJson());
    //     return $cart_list;
    // }

    /**
     * Get tags list for form dropdown.
     *
     * @return array
     */
    public static function tags()
    {
        return DB::table('tags')->orderBy('name', 'asc')->pluck('name', 'id');
    }

    /**
     * Get list of the delivery boys of the vendor
     *
     * @return array
     */
    public static function deliveryBoys($shop_id = null)
    {
        $shop_id = $shop_id ?? Auth::user()->merchantId();

        return DB::table('delivery_boys')
            ->where('shop_id', $shop_id)
            ->where('status', BaseModel::ACTIVE)
            ->orderBy('nice_name', 'asc')
            ->pluck('nice_name', 'id');
    }

    /**
     * Return attribute list for the given product
     *
     * @param Product $product
     * @return collection
     */
    public static function getAttributesBy(Product $product)
    {
        if ($attrs = $product->categories->pluck('attrsList')) {
            return $attrs->flatten()->unique('id');
        }

        return $attrs;
    }
}
