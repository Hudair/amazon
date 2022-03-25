<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Banner;
use App\Models\Attribute;
use App\Models\Country;
use App\Models\Inventory;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Slider;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\CategorySubGroup;
use App\Helpers\ListHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $sliders = Cache::rememberForever('sliders', function () {
            return Slider::orderBy('order', 'asc')
                ->with([
                    'featureImage:path,imageable_id,imageable_type',
                    'mobileImage:path,imageable_id,imageable_type',
                ])
                ->get()->toArray();
        });

        $banners = Cache::rememberForever('banners', function () {
            return Banner::with('featureImage:path,imageable_id,imageable_type')
                ->orderBy('order', 'asc')->get()->groupBy('group_id')->toArray();
        });

        //Trending Category Load With Images
        $trending_categories = get_trending_categories();

        //Featured Category Load With Images
        $featured_category = get_featured_category();

        //Featured Brands
        $featured_brands = get_featured_brands();

        // Deal of the day;
        $deal_of_the_day = get_deal_of_the_day();

        // Get featured items
        $featured_items = get_featured_items();
        // dd($featured_items->toArray());
        $daily_popular = Cache::remember('daily_popular', config('cache.remember.daily_popular', 0), function () {
            return ListHelper::popular_items(config('system.popular.period.daily', 1), config('system.popular.take.daily', 5));
        });
        $weekly_popular = Cache::remember('weekly_popular', 86400, function () {
            return ListHelper::popular_items(config('system.popular.period.weekly', 7), config('system.popular.take.weekly', 5));
        });
        $monthly_popular = Cache::remember('monthly_popular', 86400, function () {
            return ListHelper::popular_items(config('system.popular.period.monthly', 30), config('system.popular.take.monthly', 5));
        });

        // echo "<pre>"; print_r($monthly_popular->toArray()); echo "<pre>"; exit('end!');
        //Recently Added Items
        $recent = ListHelper::latest_available_items(10);

        //Additional Items
        $additional_items = ListHelper::random_items(10);

        //Bundle Offer:
        // $bundle_offer = ListHelper::random_items(18);

        // Best deal under the amount:
        $deals_under = Cache::remember('deals_under', config('cache.remember.deals', 0), function () {
            return ListHelper::best_find_under(get_from_option_table('best_finds_under', 99));
        });

        // Flash Deal Items
        if (is_incevio_package_loaded('flashdeal')) {
            $flashdeals = Cache::remember('flashdeals', config('cache.remember.deals', 0), function () {
                return get_flash_deals();
            });

            // Check if the deal is still valid 
            if (
                $flashdeals &&
                $flashdeals['start_time']->isPast() &&
                $flashdeals['end_time']->isFuture()
            ) {
                View::share('flashdeals', $flashdeals);
            }
        }

        // Best Selling now:
        // $best_selling = ListHelper::random_items(18);

        // For legacy theme support. Will be removed in future
        if (active_theme() == 'legacy' || active_theme() == 'martfury') {
            $trending = ListHelper::popular_items(config('system.popular.period.trending', 2), config('system.popular.take.trending', 15));
            View::share('trending', $trending);
        }

        $trending = ListHelper::popular_items(config('system.popular.period.trending', 2), config('system.popular.take.trending', 12));

        return view('theme::index', compact(
            'banners',
            'sliders',
            'daily_popular',
            'weekly_popular',
            'monthly_popular',
            'recent',
            'additional_items',
            'trending_categories',
            'featured_items',
            'deal_of_the_day',
            'deals_under',
            'featured_category',
            'featured_brands'
        ));
    }

    /**
     * Browse category based products
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function browseCategory(Request $request, $slug, $sortby = null)
    {
        $category = Category::where('slug', $slug)
            ->with([
                'subGroup' => function ($q) {
                    $q->select(['id', 'slug', 'name', 'category_group_id'])->active();
                },
                'subGroup.group' => function ($q) {
                    $q->select(['id', 'slug', 'name'])->active();
                },
                'attrsList' => function ($q) {
                    $q->with('attributeValues');
                }
            ])
            ->active()->firstOrFail();

        // Take only available items
        $all_products = $category->listings()->available()->filter($request->all());

        // Parameter for filter options
        $brands = ListHelper::get_unique_brand_names_from_linstings($all_products);
        $priceRange = ListHelper::get_price_ranges_from_linstings($all_products);

        // Filter results
        $products = $all_products
            // ->withCount([
            //     'orders' => function ($query) {
            //         $query->where('order_items.created_at', '>=', Carbon::now()->subHours(config('system.popular.hot_item.period', 24)));
            //     },
            // ])
            ->with([
                'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                'shop:id,slug,name,id_verified,phone_verified,address_verified',
                'images:path,imageable_id,imageable_type',
            ])
            ->paginate(config('system.view_listing_per_page', 16))
            ->appends($request->except('page'));

        return view('theme::category', compact('category', 'products', 'brands', 'priceRange'));
    }

    /**
     * Browse listings by category sub group
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function browseCategorySubGrp(Request $request, $slug, $sortby = null)
    {
        $categorySubGroup = CategorySubGroup::where('slug', $slug)
            ->with([
                'categories' => function ($q) {
                    $q->select(['id', 'slug', 'category_sub_group_id', 'name'])->whereHas('listings')->active();
                },
                'categories.listings' => function ($q) use ($request) {
                    $q->available()->filter($request->all())
                        ->withCount([
                            'orders' => function ($query) {
                                $query->where('order_items.created_at', '>=', Carbon::now()->subHours(config('system.popular.hot_item.period', 24)));
                            },
                        ])
                        ->with([
                            'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                            'shop:id,slug,name,id_verified,phone_verified,address_verified',
                            'images:path,imageable_id,imageable_type',
                        ])->get();
                },
            ])
            ->active()->firstOrFail();

        $all_products = prepareFilteredListingsNew($request, $categorySubGroup->categories);
        // $all_products = prepareFilteredListings($request, $categorySubGroup);

        // Get brands ans price ranges
        $brands = ListHelper::get_unique_brand_names_from_linstings($all_products);
        $priceRange = ListHelper::get_price_ranges_from_linstings($all_products);

        // Paginate the results
        $products = $all_products->paginate(config('system.view_listing_per_page', 16))
            ->appends($request->except('page'));

        return view('theme::category_sub_group', compact('categorySubGroup', 'products', 'brands', 'priceRange'));
    }

    /**
     * Browse listings by category group
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function browseCategoryGroup(Request $request, $slug, $sortby = null)
    {
        $categoryGroup = CategoryGroup::where('slug', $slug)->with([
            'categories' => function ($q) {
                $q->select(['categories.id', 'categories.slug', 'categories.category_sub_group_id', 'categories.name'])
                    ->where('categories.active', 1)->whereHas('listings')->withCount('listings');
            },
            'categories.listings' => function ($q) use ($request) {
                $q->available()->filter($request->all())
                    ->withCount([
                        'orders' => function ($query) {
                            $query->where('order_items.created_at', '>=', Carbon::now()->subHours(config('system.popular.hot_item.period', 24)));
                        },
                    ])
                    ->with([
                        'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                        'shop:id,slug,name,id_verified,phone_verified,address_verified',
                        'images:path,imageable_id,imageable_type',
                    ])->get();
            },
        ])->active()->firstOrFail();

        $all_products = prepareFilteredListingsNew($request, $categoryGroup->categories);
        // $all_products = prepareFilteredListings($request, $categoryGroup);

        // Get brands ans price ranges
        $brands = ListHelper::get_unique_brand_names_from_linstings($all_products);
        $priceRange = ListHelper::get_price_ranges_from_linstings($all_products);

        // Paginate the results
        $products = $all_products->paginate(config('system.view_listing_per_page', 16))
            ->appends($request->except('page'));

        return view('theme::category_group', compact('categoryGroup', 'products', 'brands', 'priceRange'));
    }

    /**
     * Open product page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function product($slug)
    {
        // DB::enableQueryLog();
        $item = Inventory::where('slug', $slug)->withCount('feedbacks')->available()->first();
        //return $item;
        // dd(DB::getQueryLog());
        if (!$item) {
            return view('theme::exceptions.item_not_available');
        }

        // $item = $item->loadCount('feedbacks');

        $item->load([
            'product' => function ($q) use ($item) {
                $q->select('id', 'brand', 'model_number', 'mpn', 'gtin', 'gtin_type', 'origin_country', 'slug', 'description', 'manufacturer_id', 'sale_count', 'created_at')
                    ->withCount(['inventories' => function ($query) use ($item) {
                        $query->where('shop_id', '!=', $item->shop_id)->available();
                    }]);
            },
            'attributeValues' => function ($q) {
                $q->select('id', 'attribute_values.attribute_id', 'value', 'color', 'order')->with('attribute:id,name,attribute_type_id,order');
            },
            'shop' => function ($q) {
                $q->withCount('inventories')
                    ->with([
                        'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                        'latestFeedbacks' => function ($q) {
                            $q->with('customer:id,nice_name,name');
                        },
                    ]);
            },
            'latestFeedbacks' => function ($q) {
                $q->with('customer:id,nice_name,name');
            },
            'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
            'images:id,path,imageable_id,imageable_type',
            'tags:id,name',
        ]);

        $this->update_recently_viewed_items($item); //update_recently_viewed_items

        $variants = ListHelper::variants_of_product($item, $item->shop_id);
        //return $variants;
        $attr_pivots = DB::table('attribute_inventory')
            ->select('attribute_id', 'inventory_id', 'attribute_value_id')
            ->whereIn('inventory_id', $variants->pluck('id'))->get();

        $item_attrs = $attr_pivots->where('inventory_id', $item->id)
            ->pluck('attribute_value_id')->toArray();

        $attributes = Attribute::select('id', 'name', 'attribute_type_id', 'order')
            ->whereIn('id', $attr_pivots->pluck('attribute_id'))
            ->with(['attributeValues' => function ($query) use ($attr_pivots) {
                $query->whereIn('id', $attr_pivots->pluck('attribute_value_id'))->orderBy('order');
            }])
            ->orderBy('order')->get();
        //return $attributes;
        // TEST
        $related = ListHelper::related_products($item);
        $linked_items = ListHelper::linked_items($item);

        if (!$linked_items->count()) {
            $linked_items = $related->random($related->count() >= 3 ? 3 : $related->count());
        }

        // Country list for ship_to dropdown
        $business_areas = Cache::rememberForever('countries_cached', function () {
            return Country::select('id', 'name', 'iso_code')->orderBy('name', 'asc')->get();
        });

        return view('theme::product', compact('item', 'variants', 'attributes', 'item_attrs', 'related', 'linked_items', 'business_areas'));
    }

    /**
     * Open product quick review modal
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function quickViewItem($slug)
    {
        $item = Inventory::where('slug', $slug)
            ->available()
            ->with([
                'images:path,imageable_id,imageable_type',
                'product' => function ($q) {
                    $q->select('id', 'slug')
                        ->withCount(['inventories' => function ($query) {
                            $query->available();
                        }]);
                },
            ])
            ->withCount('feedbacks')->firstOrFail();

        $this->update_recently_viewed_items($item); //update_recently_viewed_items

        $variants = ListHelper::variants_of_product($item, $item->shop_id);

        $attr_pivots = DB::table('attribute_inventory')
            ->select('attribute_id', 'inventory_id', 'attribute_value_id')
            ->whereIn('inventory_id', $variants->pluck('id'))->get();

        $attributes = Attribute::select('id', 'name', 'attribute_type_id', 'order')
            ->whereIn('id', $attr_pivots->pluck('attribute_id'))
            ->with([
                'attributeValues' => function ($query) use ($attr_pivots) {
                    $query->whereIn('id', $attr_pivots->pluck('attribute_value_id'))->orderBy('order');
                },
            ])
            ->orderBy('order')->get();

        return view('theme::modals.quickview', compact('item', 'attributes'))->render();
    }

    /**
     * Open shop page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function offers($slug)
    {
        $product = Product::where('slug', $slug)
            ->with([
                'inventories' => function ($q) {
                    $q->available();
                },
                'inventories.attributeValues.attribute',
                'inventories.avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                // 'inventories.feedbacks:rating,feedbackable_id,feedbackable_type',
                'inventories.shop.feedbacks:rating,feedbackable_id,feedbackable_type',
                'inventories.shop.image:path,imageable_id,imageable_type',
            ])
            ->firstOrFail();

        return view('theme::offers', compact('product'));
    }

    /**
     * Open brand list page
     *
     * @return \Illuminate\Http\Response
     */
    public function all_brands()
    {
        $brands = Manufacturer::select('id', 'slug', 'name')->active()->with('logoImage')->paginate(24);

        return view('theme::brand_lists', compact('brands'));
    }

    /**
     * Open shop list page
     *
     * @return \Illuminate\Http\Response
     */
    public function all_shops()
    {
        $shops = Shop::select('id', 'slug', 'name', 'id_verified', 'phone_verified', 'address_verified')
            ->with('logoImage')
            ->active()->paginate(24);

        return view('theme::shop_lists', compact('shops'));
    }

    /**
     * Open shop page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function shop($slug)
    {
        $shop = Shop::where('slug', $slug)->active()
            ->with([
                'feedbacks' => function ($q) {
                    $q->with('customer:id,nice_name,name')->latest()->take(10);
                },
            ])
            ->withCount([
                'inventories' => function ($q) {
                    $q->available();
                },
            ])
            ->firstOrFail();

        // Check shop maintenance_mode
        if (getShopConfig($shop->id, 'maintenance_mode')) {
            return response()->view('theme::errors.503', [], 503);
        }

        return view('theme::shop', compact('shop'));
    }

    /**
     * Open shop page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function shopProducts($slug)
    {
        $shop = Shop::where('slug', $slug)->active()->firstOrFail();

        // Check shop maintenance_mode
        if (getShopConfig($shop->id, 'maintenance_mode')) {
            return response()->view('theme::errors.503', [], 503);
        }

        $products = Inventory::where('shop_id', $shop->id)
            ->groupBy('product_id')
            ->filter(request()->all())
            ->with([
                'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                'images:path,imageable_id,imageable_type',
            ])
            ->withCount(['orders' => function ($q) {
                $q->where('order_items.created_at', '>=', Carbon::now()->subHours(config('system.popular.hot_item.period', 24)));
            }])
            ->available()->inRandomOrder()->paginate(15);

        return view('theme::shop', compact('shop', 'products'));
    }

    /**
     * Open brand page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function brand($slug)
    {
        $brand = Manufacturer::where('slug', $slug)->firstOrFail();

        return view('theme::brand', compact('brand'));
    }

    /**
     * Open brand page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function brandProducts($slug)
    {
        $brand = Manufacturer::where('slug', $slug)->firstOrFail();

        $ids = Product::where('manufacturer_id', $brand->id)->pluck('id');

        $products = Inventory::whereIn('product_id', $ids)
            ->groupBy('product_id', 'shop_id')
            ->filter(request()->all())
            ->whereHas('shop', function ($q) {
                $q->select(['id', 'current_billing_plan', 'active'])->active();
            })
            ->with([
                'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
                'images:path,imageable_id,imageable_type',
            ])
            ->withCount([
                'orders' => function ($q) {
                    $q->where('order_items.created_at', '>=', Carbon::now()->subHours(config('system.popular.hot_item.period', 24)));
                },
            ])
            ->active()->inRandomOrder()->paginate(15);

        return view('theme::brand', compact('brand', 'products'));
    }

    /**
     * Display the category list page.
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        return view('theme::categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function openPage($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return view('theme::page', compact('page'));
    }

    /**
     * Push product ID to session for the recently viewed items section
     *
     * @param  [type] $item [description]
     */
    private function update_recently_viewed_items($item)
    {
        $items = Session::get('products.recently_viewed_items', []);

        if (!in_array($item->getKey(), $items)) {
            Session::push('products.recently_viewed_items', $item->getKey());
        }

        Cache::forget('recently_viewed_items');
    }
}
