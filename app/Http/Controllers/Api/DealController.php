<?php

namespace App\Http\Controllers\Api;

// use App\Shop;
// use App\Product;
// use App\Category;
use App\Models\Inventory;
use App\Helpers\ListHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DealOfTheDayResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ItemResource;
// use App\Http\Resources\ItemLightResource;
use App\Http\Resources\ListingResource;
// use App\Http\Resources\AttributeResource;
// use App\Http\Resources\ShopListingResource;
// use App\Http\Resources\ManufacturerResource;
// use App\Http\Resources\ShippingOptionResource;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

// use Carbon\Carbon;

class DealController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function flashDeals()
    {
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
                $listings = null;
                $featured = null;

                if ($flashdeals['listings']) {
                    $listings = ListingResource::collection($flashdeals['listings']);
                }

                if ($flashdeals['featured']) {
                    $featured = ListingResource::collection($flashdeals['featured']);
                }

                // return $flashdeals;
                return [
                    'listings' => $listings,
                    'featured' => $featured,
                    'meta' => [
                        'deal_title' => trans('theme.flash_deals'),
                        'end_time' => $flashdeals['end_time'],
                    ],
                ];
            }
        }

        return [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function underPrice()
    {
        $price = get_from_option_table('best_finds_under', 99);

        $listings = Cache::remember(
            'deals_under',
            config('cache.remember.deals', 0),
            function () use ($price) {
                return ListHelper::best_find_under($price);
            }
        );

        return ListingResource::collection($listings)->additional([
            'meta' => [
                'deal_title' => trans('theme.best_find_under', ['amount' => get_formated_currency($price)]),
                'deals_under_price' => $price,
            ],
        ]);
    }

    /**
     * Display deal of the day;
     *
     * @return \Illuminate\Http\Response
     */
    public function dealOfTheDay(Request $request)
    {
        $item = get_deal_of_the_day();

        return new DealOfTheDayResource($item);
    }

    public function tagline()
    {
        $tagline = get_promotional_tagline();

        return [
            'tagline' => $tagline['text'],
            'link' => url($tagline['action_url']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $slug item_slug
     *
     * @return \Illuminate\Http\Response
     */
    public function item(Request $request, $slug)
    {
        $item = Inventory::where('slug', $slug)->available()
            ->with('avgFeedback:rating,count,feedbackable_id,feedbackable_type')
            // ->withCount('feedbacks')
            ->firstOrFail();

        $item->load([
            'product' => function ($q) {
                $q->select('id', 'name', 'slug', 'model_number', 'brand', 'mpn', 'gtin', 'gtin_type', 'description', 'origin_country', 'manufacturer_id', 'created_at')
                    ->withCount(['inventories' => function ($query) {
                        $query->available();
                    }]);
            },
            'attributeValues' => function ($q) {
                $q->select('id', 'attribute_values.attribute_id', 'value', 'color', 'order')
                    ->with('attribute:id,name,attribute_type_id,order')->orderBy('order');
            },
            'latestFeedbacks' => function ($q) {
                $q->with('customer:id,nice_name,name');
            },
            // 'feedbacks.customer:id,nice_name,name',
            // 'feedbacks.customer.image:path,imageable_id,imageable_type',
            'image:id,path,imageable_id,imageable_type',
        ]);

        $variants = Inventory::select(['id'])
            ->where(['product_id' => $item->product_id, 'shop_id' => $item->shop_id])
            ->with(['images', 'attributes.attributeType', 'attributeValues'])->available()->get();

        $attrs = $variants->pluck('attributes')->flatten(1)->toArray();
        $attrVs = $variants->pluck('attributeValues')->flatten(1)->toArray();

        $tempArr = [];
        foreach ($attrs as $key => $attr) {
            $tempArr[] = [
                'id' => $attr['id'],
                'type' => $attr['attribute_type']['type'],
                'name' => $attr['name'],
                'value' => [
                    'id' => $attrVs[$key]['id'],
                    'name' => $attrVs[$key]['value'],
                ],
                'color' => $attrVs[$key]['color'],
            ];
        }

        $uniqueAttrs = array_unique($tempArr, SORT_REGULAR);

        $attributes = [];
        foreach ($uniqueAttrs as $attr) {
            $attributes[$attr['id']]['name'] = $attr['name'];
            $attributes[$attr['id']]['value'][$attr['value']['id']] = $attr['value']['name'];
        }

        // Shipping Zone
        $geoip = geoip(get_visitor_IP()); // Set the location of the user
        $shipping_country_id = get_id_of_model('countries', 'iso_code', $geoip->iso_code);

        return (new ItemResource($item))->additional([
            'variants' => [
                'images' => ImageResource::collection($variants->pluck('images')->flatten(1)),
                'attributes' => $attributes,
            ],
            'shipping_country_id' => $shipping_country_id,
            'shipping_options' => $this->get_shipping_options($item, $shipping_country_id, $geoip->state),
            'countries' => ListHelper::countries(), // Country list for ship_to dropdown
        ]);
    }
}
