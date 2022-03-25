<?php

namespace App\Common;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

/**
 * Attach this Trait to a User (or other model) for easier read/writes on Addresses
 *
 * @author Munna Khan
 */
trait ShoppingCart
{
    /**
     * Get all carts of a user.
     *
     * @return App\Models\Cart
     */
    private function getShoppingCarts()
    {
        $carts = Cart::whereNull('customer_id')->where('ip_address', get_visitor_IP());

        if (Auth::guard('customer')->check()) {
            $carts = $carts->orWhere('customer_id', Auth::guard('customer')->user()->id);
        } elseif (Auth::guard('api')->check()) {
            $carts = $carts->orWhere('customer_id', Auth::guard('api')->user()->id);
        }

        return $carts->with('shop:id,slug,name', 'shop.logo:path,imageable_id,imageable_type')
            ->get();
    }

    /**
     * Add given item to cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request, $slug)
    {
        $item = Inventory::where('slug', $slug)->first();

        if (!$item) {
            return response()->json(trans('theme.item_not_available'), 404);
        }

        $customer_id = $this->getCartOwnerId($request);

        if ($customer_id) {
            $old_cart = Cart::where('shop_id', $item->shop_id)
                ->where(function ($query) use ($customer_id) {
                    $query->where('customer_id', $customer_id)
                        ->orWhere(function ($q) {
                            $q->whereNull('customer_id')->where('ip_address', get_visitor_IP());
                        });
                })->first();
        } else {
            $old_cart = Cart::where('shop_id', $item->shop_id)
                ->whereNull('customer_id')
                ->where('ip_address', get_visitor_IP())->first();
        }

        // Check if the item is alrealy in the cart
        if ($old_cart) {
            $item_in_cart = DB::table('cart_items')->where('cart_id', $old_cart->id)
                ->where('inventory_id', $item->id)->first();

            // Item alrealy in cart
            if ($item_in_cart) {
                return response()->json(['cart_id' => $item_in_cart->cart_id], 444);
            }
        }

        $qtt = $request->quantity ?? $item->min_order_quantity;
        $unit_price = $item->current_sale_price();

        // Instantiate new cart if old cart not found for the shop and customer
        $cart = $old_cart ?? new Cart;
        $cart->shop_id = $item->shop_id;
        $cart->customer_id = $customer_id;
        $cart->ip_address = get_visitor_IP();
        $cart->shipping_zone_id = $request->shippingZoneId;
        $cart->item_count = $old_cart ? ($old_cart->item_count + 1) : 1;
        $cart->quantity = $old_cart ? ($old_cart->quantity + $qtt) : $qtt;

        if ($request->shipTo) {
            $cart->ship_to = $request->shipTo;
        }

        if ($request->shipToCountryId) {
            $cart->ship_to_country_id = $request->shipToCountryId;
        }

        if ($request->shipToStateId) {
            $cart->ship_to_state_id = $request->shipToStateId;
        }

        // Reset if the old cart exist, bcoz shipping rate may change after adding new item
        $cart->shipping_rate_id = $old_cart ? null : ($request->shippingRateId == 'Null' ? null : $request->shippingRateId);

        $cart->handling = $cart->get_handling_cost();
        $cart->total = $old_cart ? ($old_cart->total + ($qtt * $unit_price)) : ($qtt * $unit_price);
        // $cart->packaging_id = $old_cart ? $old_cart->packaging_id : 1;

        // All items need to have shipping_weight to calculate shipping
        // If any one the item missing shipping_weight set null to cart shipping_weight
        if ($item->shipping_weight == null || ($old_cart && $old_cart->shipping_weight == null)) {
            $cart->shipping_weight = null;
        } else {
            $cart->shipping_weight = $old_cart ?
                ($old_cart->shipping_weight + $item->shipping_weight) : $item->shipping_weight;
        }

        // Set taxes
        if ($cart->shipping_zone_id) {
            $cart->taxrate = optional($cart->shippingZone->tax)->taxrate;
            $cart->taxes = $cart->get_tax_amount();
        }

        $cart->grand_total = $cart->calculate_grand_total();
        $cart->save();

        // Makes item_description field
        $attributes = implode(' - ', $item->attributeValues->pluck('value')->toArray());
        // Prepare pivot data
        $cart_item_pivot_data = [];
        $cart_item_pivot_data[$item->id] = [
            'inventory_id' => $item->id,
            'item_description' => $item->sku . ': ' . $item->title . ' - ' . $attributes . ' - ' . $item->condition,
            'quantity' => $qtt,
            'unit_price' => $unit_price,
        ];

        // Save cart items into pivot
        if (!empty($cart_item_pivot_data)) {
            $cart->inventories()->syncWithoutDetaching($cart_item_pivot_data);
        }

        return response()->json($cart->toArray(), 200);
    }

    /**
     * Get the payable amount of give carts or all
     *
     * @param Cart $carts
     * @return numaric | null
     */
    public function getTotalPayable($carts = null)
    {
        if (!$carts) {
            $carts = $this->getShoppingCarts();
        } elseif ($carts instanceof Cart) {
            return $carts->calculate_grand_total();
        }

        $payable = 0;

        foreach ($carts as $cart) {
            $payable += $cart->calculate_grand_total();
        }

        return $payable > 0 ? $payable : null;
    }

    /**
     * Create a new order from the cart
     *
     * @param  Request $request
     * @param  App\Models\Cart $cart
     *
     * @return App\Models\Order
     */
    private function saveOrderFromCart(Request $request, Cart $cart)
    {
        // Set shipping_rate_id and handling cost to NULL if its free shipping
        // if ($cart->is_free_shipping()) {
        //     $cart->shipping_rate_id = Null;
        //     $cart->handling = Null;
        // }

        // Save the order
        $order = new Order;
        $order->fill(
            array_merge($cart->toArray(), [
                'customer_id' => $this->getCartOwnerId($request, $cart),
                'payment_method_id' => $request->payment_method_id ?? $cart->payment_method_id,
                'grand_total' => $cart->calculate_grand_total(),
                'order_number' => get_formated_order_number($cart->shop_id),
                'carrier_id' => $cart->carrier() ? $cart->carrier->id : null,
                'shipping_address' => $request->shipping_address ?? $cart->shipping_address,
                'billing_address' => $request->shipping_address ?? $cart->shipping_address,
                'email' => $request->email ?? $cart->email,
                'customer_phone_number' => $request->phone,
                'buyer_note' => $request->buyer_note,
                'device_id' => $request->device_id ?? $cart->device_id,
            ])
        )->save();

        if ($request->has('prescription')) {
            $file = $request->file('prescription');

            // Request from API
            if ($request->is('api/*')) {
                $file = create_file_from_base64($request->get('prescription'));
            }

            $order->saveAttachments($file);
        }

        // This has to be after save the order
        if ($payment_instruction = $order->menualPaymentInstructions()) {
            $order->forceFill(['payment_instruction' => $payment_instruction])->save();
        }

        // Add order item into pivot table
        $cart_items = $cart->inventories->pluck('pivot');
        $order_items = [];
        foreach ($cart_items as $item) {
            $order_items[] = [
                'order_id'          => $order->id,
                'inventory_id'      => $item->inventory_id,
                'item_description'  => $item->item_description,
                'quantity'          => $item->quantity,
                'unit_price'        => $item->unit_price,
                'created_at'        => $item->created_at,
                'updated_at'        => $item->updated_at,
            ];
        }

        DB::table('order_items')->insert($order_items);

        // Sync up the inventory. Decrease the stock of the order items from the listing
        foreach ($order->inventories as $item) {
            $item->decrement('stock_quantity', $item->pivot->quantity);
        }

        // Reduce the coupone in use
        if ($order->coupon_id) {
            Coupon::find($order->coupon_id)->decrement('quantity');
        }

        // Delete the cart
        $cart->forceDelete();

        return $order;
    }

    /**
     * Revert order to cart
     *
     * @param  App\Models\Order $Order
     *
     * @return App\Models\Cart
     */
    private function moveAllItemsToCartAgain($order, $revert = false, $cart = null)
    {
        if (!$order instanceof Order) {
            $order = Order::find($order);
        }

        if (!$order) {
            return;
        }

        // Set time
        $now = Carbon::now();

        // Fill the cart data
        $data = [
            'shop_id' => $order->shop_id,
            'customer_id' => $order->customer_id,
            'ship_to' => $order->ship_to,
            'shipping_zone_id' => $order->shipping_zone_id,
            'shipping_rate_id' => $order->shipping_rate_id,
            // 'ship_to_country_id' => $order->ship_to_country_id,
            // 'ship_to_state_id' => $order->ship_to_state_id,
            'packaging_id' => $order->packaging_id,
            'item_count' => $order->item_count,
            'quantity' => $order->quantity,
            'taxrate' => $order->taxrate,
            'shipping_weight' => $order->shipping_weight,
            'total' => $order->total,
            'shipping' => $order->shipping,
            'packaging' => $order->packaging,
            'handling' => $order->handling,
            'taxes' => $order->taxes,
            'grand_total' => $order->grand_total,
            'email' => $order->email,
            'ip_address' => get_visitor_IP(),
            'created_at' => $revert ? $order->created_at : $now,
            'updated_at' => $revert ? $order->updated_at : $now,
        ];

        // Keep the old cart id
        if ($cart) {
            $data = array_merge(['id' => $cart], $data);
        }

        // Save the cart
        $cart = Cart::forceCreate($data);

        // Add order item into cart pivot table
        $cart_items = [];
        $quantity = 0;
        $shipping_weight = 0;
        $total = 0;
        // $grand_total = 0;

        foreach ($order->inventories as $item) {
            // Skip if the item is out of stock
            if (!$item->stock_quantity > 0) {
                Session::flash('warning', trans('messages.some_item_out_of_stock'));
                continue;
            }

            // Get current updated price
            $unit_price = $item->current_sale_price();

            // Set qtt after checking availablity
            $item_qtt = $item->stock_quantity >= $item->pivot->quantity ?
                $item->pivot->quantity : $item->stock_quantity;

            $shipping_weight += $item->shipping_weight;
            $quantity += $item_qtt;
            $total += $item_qtt * $unit_price;

            $cart_items[] = [
                'cart_id'           => $cart->id,
                'inventory_id'      => $item->pivot->inventory_id,
                'item_description'  => $item->pivot->item_description,
                'quantity'          => $item_qtt,
                'unit_price'        => $unit_price,
                'created_at'        => $revert ? $item->pivot->created_at : $now,
                'updated_at'        => $revert ? $item->pivot->created_at : $now,
            ];

            // Sync up the inventory. Increase the stock of the order items from the listing
            if ($revert) {
                $item->increment('stock_quantity', $item->pivot->quantity);
            }
        }

        DB::table('cart_items')->insert($cart_items);

        if ($revert) {
            // Increment the coupone in use
            if ($order->coupon_id) {
                Coupon::find($order->coupon_id)->increment('quantity');
            }

            $order->forceDelete();   // Delete the order
        }

        // Update cart
        $cart->quantity = $quantity;
        $cart->shipping_weight = $shipping_weight;
        $cart->total = $total;
        $cart->grand_total = $cart->calculate_grand_total();
        $cart->updated_at = $cart->updated_at;
        $cart->taxes = $cart->get_tax_amount();
        $cart->save();

        return $cart;
    }

    /**
     * Revert order to cart
     *
     * @param  App\Models\Order $Order
     *
     * @return App\Models\Cart
     */
    private function getCartOwnerId($request, $cart = null)
    {
        if ($cart && $cart->customer_id) {
            return $cart->customer_id;
        }

        if (Auth::guard('customer')->check()) {
            return Auth::guard('customer')->user()->id;
        }

        if (Auth::guard('api')->check()) {
            return Auth::guard('api')->user()->id;
        }

        if ($request->api_token) {
            $customer = Customer::where('api_token', $request->api_token)->first();

            return $customer ? $customer->id : null;
        }

        return null;
    }
}
