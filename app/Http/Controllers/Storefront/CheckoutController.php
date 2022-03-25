<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\State;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Common\ShoppingCart;
use App\Helpers\ListHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\DirectCheckoutRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    use ShoppingCart;

    /**
     * Checkout the specified cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request, Cart $cart)
    {
        if (!crosscheckCartOwnership($request, $cart)) {
            return redirect()->route('cart.index')
                ->with('warning', trans('theme.notify.please_login_to_checkout'));
        }

        $cart = crosscheckAndUpdateOldCartInfo($request, $cart);

        $shop = Shop::where('id', $cart->shop_id)->active()
            ->with([
                'config',
                'packagings' => function ($query) {
                    $query->active();
                },
            ])->first();

        // Abort if the shop is not exist or inactive
        abort_unless($shop, 406, trans('theme.notify.store_not_available'));

        if (vendor_get_paid_directly()) {
            $shop->load(['paymentMethods' => function ($q) {
                $q->active();
            }]);

            $paymentMethods = $shop->paymentMethods;
            if (!$paymentMethods) {
                return redirect()->route('cart.index')
                    ->with('warning', trans('theme.notify.seller_has_no_payment_method'));
            }
        } else {
            $paymentMethods = PaymentMethod::active()->get();
        }

        // Load related models
        $cart->load('coupon:id,shop_id,name,code,value,min_order_amount,type');

        $customer = Auth::guard('customer')->check() ? Auth::guard('customer')->user() : null;

        $business_areas = Country::select('id', 'name', 'iso_code')
            ->orderBy('name', 'asc')->get();

        // Sate list of the country for ship_to dropdown
        $states = $cart->ship_to_state_id ? ListHelper::states($cart->ship_to_country_id) : [];

        // Get platform's default packaging
        $platformDefaultPackaging = getPlatformDefaultPackaging();

        $geoip = geoip(get_visitor_IP());

        $geoip_country = $business_areas->where('iso_code', $geoip->iso_code)->first();

        $geoip_state = State::select('id', 'name', 'iso_code', 'country_id')
            ->where('iso_code', $geoip->state)
            ->where('country_id', $geoip_country->id)
            ->first();

        $country_id = $cart->ship_to_country_id ?? $geoip_country->id;
        $state_id = $cart->ship_to_state_id ?? optional($geoip_state)->id;

        $shipping_zones[$cart->id] = get_shipping_zone_of($cart->shop_id, $country_id, $state_id);

        $shipping_options[$cart->id] = isset($shipping_zones[$cart->id]->id) ? getShippingRates($shipping_zones[$cart->id]->id) : 'NaN';

        return view('theme::checkout', compact('cart', 'customer', 'shop', 'business_areas', 'shipping_zones', 'shipping_options', 'states', 'paymentMethods', 'platformDefaultPackaging'));
    }

    /**
     * Direct checkout with the item/cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function directCheckout(DirectCheckoutRequest $request, $slug)
    {
        $cart = $this->addToCart($request, $slug);

        if (200 == $cart->status()) {
            return redirect()->route('cart.index', $cart->getdata()->id);
        } elseif (444 == $cart->status()) {
            return redirect()->route('cart.index', $cart->getdata()->cart_id);
        }

        return redirect()->back()->with('warning', trans('theme.notify.failed'));
    }
}
