<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Packaging;
use App\Models\Inventory;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\ShippingOptionResource;
use App\Http\Requests\Validations\ApiUpdateCartRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $expressId = null)
    {
        $carts = Cart::whereNull('customer_id')->where('ip_address', get_visitor_IP());

        if (Auth::guard('api')->check()) {
            $carts = $carts->orWhere('customer_id', Auth::guard('api')->user()->id);
        }

        $carts = $carts->with('coupon:id,shop_id,name,code,value,type')->get();

        // Load related models
        $carts->load([
            'shop' => function ($q) {
                $q->with(['config', 'packagings' => function ($query) {
                    $query->active();
                }])->active();
            },
            'inventories.image',
            'shippingPackage',
        ]);

        return CartResource::collection($carts);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param  Cart    $cart
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Cart $cart)
    {
        if (crosscheckCartOwnership($request, $cart)) {
            return new CartResource($cart);
        }

        return response()->json(['message' => trans('api.auth_required')], 403);
    }

    /**
     * Add item to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request, $slug)
    {
        $item = Inventory::where('slug', $slug)->first();

        if (!$item) {
            return response()->json(['message' => trans('api.404')], 404);
        }

        $customer_id = Auth::guard('api')->check() ? Auth::guard('api')->user()->id : null;

        $old_cart = Cart::where('shop_id', $item->shop_id);

        if ($customer_id) {
            $old_cart = $old_cart->where(function ($q1) use ($customer_id) {
                $q1->where('customer_id', $customer_id)
                    ->orWhere(function ($q2) {
                        $q2->whereNull('customer_id')
                            ->where('ip_address', get_visitor_IP());
                    });
            });
        } else {
            $old_cart = $old_cart->whereNull('customer_id')
                ->where('ip_address', get_visitor_IP());
        }

        $old_cart = $old_cart->first();

        // Check the available stock limit
        if ($request->quantity > $item->stock_quantity) {
            return response()->json(['message' => trans('api.item_max_stock')], 409);
        }

        // Check if the item is alrealy in the cart
        if ($old_cart) {
            $item_in_cart = DB::table('cart_items')
                ->where('cart_id', $old_cart->id)
                ->where('inventory_id', $item->id)->first();

            // Item alrealy in cart
            if ($item_in_cart) {
                return response()->json(['message' => trans('api.item_alrealy_in_cart')], 409);
            }
        }

        $qtt = $request->quantity ?? $item->min_order_quantity;
        // $shipping_rate_id = $old_cart ? $old_cart->shipping_rate_id : $request->shippingRateId;
        $unit_price = $item->current_sale_price();

        // Instantiate new cart if old cart not found for the shop and customer
        $cart = $old_cart ?? new Cart;
        $cart->shop_id = $item->shop_id;
        $cart->customer_id = $customer_id;
        $cart->ip_address = get_visitor_IP();
        $cart->item_count = $old_cart ? ($old_cart->item_count + 1) : 1;
        $cart->quantity = $old_cart ? ($old_cart->quantity + $qtt) : $qtt;

        if ($request->ship_to) {
            $cart->ship_to = $request->ship_to;
        }

        // Reset if the old cart exist, bcoz shipping rate will change after adding new item
        if ($old_cart) {
            $cart->shipping_zone_id = null;
            $cart->shipping_rate_id = null;
            $cart->shipping = null;
        } else {
            $cart->shipping_zone_id = $request->shipping_zone_id;

            $cart->shipping_rate_id = $request->shipping_option_id == 'Null' ?
                null : $request->shipping_option_id;

            $cart->shipping = $request->shipping_option_id == 'Null' ?
                null : optional($cart->shippingRate)->rate;
        }

        $cart->handling = $cart->get_handling_cost();

        $cart->total = $old_cart ?
            ($old_cart->total + ($qtt * $unit_price)) : ($qtt * $unit_price);

        $cart->packaging_id = $old_cart ?
            $old_cart->packaging_id : Packaging::FREE_PACKAGING_ID;

        $cart->grand_total = $cart->calculate_grand_total();

        // All items need to have shipping_weight to calculate shipping
        // If any one the item missing shipping_weight set null to cart shipping_weight
        if ($item->shipping_weight == null || ($old_cart && $old_cart->shipping_weight == null)) {
            $cart->shipping_weight = null;
        } else {
            $cart->shipping_weight = $old_cart ? ($old_cart->shipping_weight + $item->shipping_weight) : $item->shipping_weight;
        }

        if ($request->ship_to_country_id) {
            $cart->ship_to_country_id = $request->ship_to_country_id;
        }

        if ($request->ship_to_state_id) {
            $cart->ship_to_state_id = $request->ship_to_state_id;
        }

        $cart->save();

        // Makes item_description field
        $attributes = implode(' - ', $item->attributeValues->pluck('value')->toArray());

        // Prepare pivot data
        $cart_item_pivot_data = [];
        $cart_item_pivot_data[$item->id] = [
            'inventory_id' => $item->id,
            'item_description' => $item->title . ' - ' . $attributes . ' - ' . $item->condition,
            'quantity' => $qtt,
            'unit_price' => $unit_price,
        ];

        // Save cart items into pivot
        if (!empty($cart_item_pivot_data)) {
            $cart->inventories()->syncWithoutDetaching($cart_item_pivot_data);
        }

        return response()->json(['message' => trans('api.item_added_to_cart')], 200);
    }

    /**
     * Update the cart and redirected to checkout page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart    $cart
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateCartRequest $request, Cart $cart)
    {
        if ($request->item && $request->quantity) {
            if (is_numeric($request->item)) {
                $item = Inventory::findOrFail($request->item);
            } else {
                $item = Inventory::where('slug', $request->item)->first();
            }

            // Check the available stock limit
            if ($request->quantity > $item->stock_quantity) {
                return response()->json(['message' => trans('api.item_max_stock')], 409);
            }

            $pivot = DB::table('cart_items')->where('cart_id', $cart->id)
                ->where('inventory_id', $item->id)->first();

            if (!$pivot) {
                return response()->json(['message' => trans('api.404')], 404);
            }

            $quantity = $request->quantity;
            $old_quantity = $pivot->quantity;

            $cart->quantity = $quantity < $item->min_order_quantity ? $item->min_order_quantity : $quantity;
            $cart->item_count = ($cart->item_count - $old_quantity) + $quantity;

            if ($item->shipping_weight) {
                $cart->shipping_weight = ($cart->shipping_weight - ($item->shipping_weight * $old_quantity)) + ($item->shipping_weight * $quantity);
            }

            $unit_price = $item->current_sale_price();

            $cart->total = ($cart->total - ($pivot->unit_price * $old_quantity)) + ($quantity * $unit_price);

            // Updating pivot data
            $cart->inventories()->updateExistingPivot($item->id, [
                'quantity' => $quantity,
                'unit_price' => $unit_price,
            ]);
        }

        if ($request->ship_to_country_id) {
            $cart->ship_to_country_id = $request->ship_to_country_id;
        }

        if ($request->ship_to_country_id) {
            $cart->ship_to_state_id = $request->ship_to_state_id ?? null;
        }

        if ($request->ship_to) {
            $cart->ship_to = $request->ship_to;
            // $zone = get_shipping_zone_of($cart->shop_id, $request->ship_to);
            // $cart->shipping_zone_id = $zone ? $zone->id : null;
            // $cart->taxrate = $zone ? getTaxRate($zone->tax_id) : null;
            // $cart->taxes = $cart->get_tax_amount();
        }

        if ($request->shipping_zone_id) {
            $cart->shipping_zone_id = $request->shipping_zone_id;
            $cart->taxrate = getTaxRate(optional($cart->shippingZone)->tax_id);
            $cart->taxes = $cart->get_tax_amount();
        }

        if ($request->shipping_option_id) {
            $cart->shipping_rate_id = $request->shipping_option_id;
            $cart->shipping = optional($cart->shippingRate)->rate;
        }

        if ($request->packaging_id) {
            $cart->packaging_id = $request->packaging_id;
            $cart->packaging = optional($cart->shippingPackage)->cost;
        }

        // Update some filed only if the cart is older than 24hrs (only to increase performance)
        if ($cart->updated_at < Carbon::now()->subHour(24)) {
            $cart->handling = getShopConfig($cart->shop_id, 'order_handling_cost');
        }

        $cart->grand_total = $cart->calculate_grand_total();
        $cart->save();

        return response()->json([
            'message' => trans('api.cart_updated'),
            'cart' => new CartResource($cart),
        ], 200);
    }

    /**
     * Remove item from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $cart = Cart::findOrFail($request->cart);

        $result = DB::table('cart_items')->where([
            ['cart_id', $request->cart],
            ['inventory_id', $request->item],
        ])->delete();

        if (!$result) {
            return response()->json(['message' => trans('api.404')], 404);
        }

        if (!$cart->inventories()->count()) {
            $cart->forceDelete();
        } else {
            crosscheckAndUpdateOldCartInfo($request, $cart);
        }

        return response()->json([
            'message' => trans('api.item_removed_from_cart'),
            'cart' => new CartResource($cart),
        ], 200);
    }

    /**
     * Return available shipping options for the specified shop.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function shipping(Request $request, Cart $cart)
    {
        $country_id = $request->ship_to_country_id ?? $cart->ship_to_country_id;
        $state_id = $request->ship_to_state_id ?? $cart->ship_to_state_id;

        // Get country and state info from user's IP
        if (!$country_id) {
            $geoip = geoip($request->ip());
            $country_id = $geoip->iso_code;
            $state_id = $geoip->state;
        }

        $zone = get_shipping_zone_of($cart->shop_id, $country_id, $state_id);

        if (!isset($zone->id)) {
            return response()->json(['message' => trans('theme.notify.seller_doesnt_ship')], 404);
        }

        return $this->get_shipping_options($cart, $zone);
    }

    /**
     * validate coupon.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateCoupon(Request $request, Cart $cart)
    {
        $coupon = Coupon::active()->where([
            ['code', $request->coupon],
            ['shop_id', $cart->shop_id],
        ])->withCount(['orders', 'customerOrders'])->first();

        if (!$coupon) {
            return response()->json([
                'message' => trans('theme.notify.coupon_not_exist')
            ], 404);
        }

        if (!$coupon->isLive() || !$coupon->isValidCustomer(Auth::guard('api')->id())) {
            return response()->json([
                'message' => trans('theme.notify.coupon_not_valid')
            ], 412);
        }

        if ($coupon->min_order_amount && $cart->total < $coupon->min_order_amount) {
            return response()->json([
                'message' => trans('theme.notify.coupon_min_order_value')
            ], 412);
        }

        if (!$coupon->isValidZone($request->zone)) {
            return response()->json([
                'message' => trans('theme.notify.coupon_not_valid_for_zone')
            ], 412);
        }

        if (!$coupon->hasQtt()) {
            return response()->json([
                'message' => trans('theme.notify.coupon_limit_expired')
            ], 412);
        }

        // Set coupon_id to the cart
        $cart->coupon_id = $coupon->id;

        // Get discounted amount
        $cart->discount = $cart->get_discounted_amount();

        // When the coupon value is bigger/equal of cart total
        if ($cart->discount >= $cart->total) {
            $cart->discount = $cart->total;
            $coupon->value = $cart->total;
        }

        // Update cart
        $cart->grand_total = $cart->calculate_grand_total();
        $cart->save();

        // The coupon is valid
        // $disc_amnt = 'percent' == $coupon->type ? ($cart->total * ($coupon->value / 100)) : $coupon->value;

        // Update the cart with coupon value
        // $cart->discount = $disc_amnt < $cart->total ? $disc_amnt : $cart->total; // Discount the amount or the cart total
        // $cart->coupon_id = $coupon->id;
        // $cart->grand_total = $cart->calculate_grand_total();
        // $cart->save();

        return response()->json([
            'message' => trans('theme.notify.coupon_applied'),
            'cart' => new CartResource($cart),
        ], 200);
    }

    /**
     * Return available shipping options for the cart
     *
     * @param  cart  $cart
     * @param  shipping zone  $zone
     *
     * @return array|null
     */
    private function get_shipping_options($cart, $zone = null)
    {
        if (!$zone) {
            return null;
        }

        $shipping_options = filterShippingOptions($zone->id, $cart->total, $cart->shipping_weight);

        if ($cart->is_free_shipping()) {
            $free_shipping[] =  json_decode(json_encode(getFreeShippingObject($zone)), FALSE);

            $shipping_options = collect($free_shipping)->merge($shipping_options);
        }

        return ShippingOptionResource::collection($shipping_options);
    }
}
