<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\CategoryGroup;
use App\Models\CategorySubGroup;
use App\Http\Controllers\Controller;
use App\Helpers\ListHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
// use Illuminate\Database\Eloquent\Collection as Eloquent;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = Inventory::search($request->input('q'))->query(function ($q1) {
            $q1->active()->groupBy('shop_id', 'product_id');
        });

        if (config('scout.driver') == 'tntsearch') {
            $items = $query->paginate(0);
        } else {
            $items = $query->get();
        }

        $items->load([
            'shop:id,slug,name,current_billing_plan,trial_ends_at,active',
            'shop.config:shop_id,maintenance_mode',
            'shop.currentSubscription',
            'shop.address',
            // 'product:id,name,gtin,model_number'
        ]);

        // Keep results only from active shops
        $items = $items->filter(function ($item) {
            return $item->shop && $item->shop->canGoLive();
        });

        $now = Carbon::now();
        $category = null;

        if ($request->has('in')) {
            $category = Category::where('slug', $request->input('in'))
                ->with('attrsList.attributeValues')->active()->firstOrFail();

            $listings = $category->listings()->available()->get();

            $items = $items->intersect($listings);
        } elseif ($request->has('insubgrp') && ($request->input('insubgrp') != 'all')) {
            $category = CategorySubGroup::where('slug', $request->input('insubgrp'))
                ->active()->firstOrFail();

            $listings = prepareFilteredListings($request, $category);

            $items = $items->intersect($listings);
        } elseif ($request->has('ingrp')) {
            $category = CategoryGroup::where('slug', $request->input('ingrp'))
                ->active()->firstOrFail();

            $listings = prepareFilteredListings($request, $category);

            $items = $items->intersect($listings);
        }

        // Attributes for filters
        $brands = ListHelper::get_unique_brand_names_from_linstings($items);
        $priceRange = ListHelper::get_price_ranges_from_linstings($items);

        if ($request->has('free_shipping')) {
            $items = $items->where('free_shipping', 1);
        }

        if ($request->has('new_arrivals')) {
            $items = $items->where('created_at', '>', $now->subDays(config('system.filter.new_arrival', 7)));
        }

        if ($request->has('has_offers')) {
            $items = $items->where('offer_price', '>', 0)
                ->where('offer_start', '<', $now)
                ->where('offer_end', '>', $now);
        }

        if ($request->has('sort_by')) {
            switch ($request->get('sort_by')) {
                case 'newest':
                    $items = $items->sortByDesc('created_at');
                    break;

                case 'oldest':
                    $items = $items->sortBy('created_at');
                    break;

                case 'price_acs':
                    $items = $items->sortBy('sale_price');
                    break;

                case 'price_desc':
                    $items = $items->sortByDesc('sale_price');
                    break;

                case 'best_match':
                default:
                    break;
            }
        }

        if ($request->has('condition')) {
            $items = $items->whereIn('condition', array_keys($request->input('condition')));
        }

        if ($request->has('price')) {
            $price = explode('-', $request->input('price'));
            $items = $items->where('sale_price', '>=', $price[0])->where('sale_price', '<=', $price[1]);
        }

        if ($request->has('brand')) {
            $items = $items->whereIn('brand', array_keys($request->input('brand')));
        }

        $products = $items->paginate(config('system.view_listing_per_page', 15));

        $products->load([
            'product' => function ($q) {
                $q->select('id')->with([
                    'categories:id,name,slug,category_sub_group_id',
                    'categories.subGroup:id,name,slug,category_group_id',
                    'categories.subGroup.group:id,name,slug',
                ]);
            },
            'avgFeedback:rating,count,feedbackable_id,feedbackable_type',
            'images:path,imageable_id,imageable_type',
        ]);

        return view('theme::search_results', compact('products', 'category', 'brands', 'priceRange'));
    }

    /**
     * Search autocomplete option.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $items = Inventory::search($request->input('q'))->query(function ($q1) {
            $q1->with(['image:path,imageable_id,imageable_type'])
                ->active()
                ->groupBy('shop_id', 'product_id');
        })->take(10)->paginate(10);

        $result = '';
        foreach ($items as $item) {
            $result .= View::make('theme::partials._autocomplete', ['item' => $item])->render();
        }

        if ($result == '') {
            $result = '<li>' . trans('theme.no_product_found') . '</li>';
        }

        return response()->json($result);
    }
}
