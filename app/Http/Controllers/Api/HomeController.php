<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Shop;
use App\Models\Slider;
use App\Models\State;
use App\Models\Banner;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Manufacturer;
use App\Models\ShippingRate;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\ShippingOptionRequest;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\SystemConfigResource;
use App\Http\Resources\ManufacturerLightResource;
use App\Http\Resources\ManufacturerResource;
use App\Http\Resources\PackagingResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\PaymentMethodResource;
use App\Http\Resources\ShippingOptionResource;
use App\Http\Resources\ShopLightResource;
use App\Http\Resources\ShopResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\StateResource;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Get system's default configs.
     *
     * @return \Illuminate\Http\Response
     */
    public function system_configs()
    {
        $config = (object) config('system_settings');

        return  new SystemConfigResource($config);
    }

    /**
     * Get announcement resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function announcement()
    {
        $announcement = get_global_announcement();

        return [
            'id' => $announcement->id,
            'body' => $announcement->parsed_body,
            'link' => url($announcement->action_url),
            'link_label' => $announcement->action_text,
            'created_by' => $announcement->user_id,
            'updated_at' => $announcement->updated_at->diffForHumans(),
        ];
    }

    /**
     * Get listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sliders()
    {
        $sliders = Slider::whereHas('mobileImage')
            ->with('mobileImage')
            ->orderBy('order', 'asc')
            ->get();

        return SliderResource::collection($sliders);
    }

    /**
     * Get listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function banners()
    {
        $banners = Banner::with(['featureImage'])
            ->orderBy('order', 'asc')
            ->get();

        return BannerResource::collection($banners);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allShops()
    {
        $shops = Shop::with([
            'logoImage:path,imageable_id,imageable_type',
            'avgFeedback:rating,count,feedbackable_id,feedbackable_type'
        ])->active()->get();

        return ShopLightResource::collection($shops);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function shop($slug)
    {
        $shop = Shop::where('slug', $slug)->active()
            ->with([
                'latestFeedbacks' => function ($q) {
                    $q->with('customer:id,nice_name,name')->take(3);
                },
            ])
            ->withCount([
                'inventories' => function ($q) {
                    $q->available();
                },
            ])
            ->firstOrFail();

        // Check shop maintenance_mode
        if ($shop->isDown()) {
            return response()->json(['message' => trans('app.marketplace_down')], 404);
        }

        return new ShopResource($shop);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allBrands()
    {
        $brands = Manufacturer::select('id', 'name', 'slug', 'description', 'country_id')
            // ->with('country:id,name')
            ->with('logoImage:path,imageable_id,imageable_type')
            ->active()->get();

        return ManufacturerLightResource::collection($brands);
    }

    public function featuredBrands()
    {
        $brands = get_featured_brands();

        return ManufacturerLightResource::collection($brands);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function brand($slug)
    {
        $brand = Manufacturer::where('slug', $slug)->firstOrFail();

        return new ManufacturerResource($brand);
    }

    /**
     * Return available packaging options for the specified shop.
     *
     * @param  string  $shop
     * @return \Illuminate\Http\Response
     */
    public function packaging($shop)
    {
        $shop = Shop::where('slug', $shop)->active()->firstOrFail();

        $platformDefaultPackaging = new PackagingResource(getPlatformDefaultPackaging());

        $packagings = PackagingResource::collection($shop->activePackagings);

        return $packagings->prepend($platformDefaultPackaging);
    }

    /**
     * Return available shipping options for the specified shop.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $shop
     * @return \Illuminate\Http\Response
     */
    public function shipping(ShippingOptionRequest $request, Shop $shop)
    {
        $shippingOptions = ShippingRate::where('shipping_zone_id', $request->zone)
            ->with('carrier:id,name')
            ->get();

        return ShippingOptionResource::collection($shippingOptions);
    }

    /**
     * Return available payment options options for the specified shop.
     *
     * @param  string  $shop
     * @return \Illuminate\Http\Response
     */
    public function paymentOptions($shop)
    {
        // Get the shop
        $shop = Shop::where('slug', $shop)->active()->firstOrFail();

        // Get all active payment methods
        $activePaymentMethods = PaymentMethod::active()->get();
        $activePaymentCodes = $activePaymentMethods->pluck('code')->toArray();

        $activePaymentMethods = $shop->paymentMethods;

        // $shop_config = null;
        // if (vendor_get_paid_directly()) {
        //     $activePaymentMethods = $shop->paymentMethods;
        //     $shop_config = $shop;
        // }

        $results = collect([]);
        foreach ($activePaymentMethods as $payment) {
            if (
                !in_array($payment->code, $activePaymentCodes) ||
                !get_payment_config_info($payment->code, $shop)
            ) {
                continue;
            }

            $results->push($payment);
        }

        return PaymentMethodResource::collection($results);
    }

    /**
     * Get active currencies.
     *
     * @return \Illuminate\Http\Response
     */
    public function currencies()
    {
        $currencies = Currency::active()->orderBy('priority', 'asc')->get();

        return CurrencyResource::collection($currencies);
    }

    /**
     * Get active currencies.
     *
     * @return \Illuminate\Http\Response
     */
    public function currency()
    {
        $currency = Currency::active()->orderBy('priority', 'asc')->get();

        return CurrencyResource::collection($currency);
    }

    /**
     * Get country list resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries()
    {
        $countries = Country::select('id', 'name', 'iso_code')->get();

        return CountryResource::collection($countries);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $country
     * @return \Illuminate\Http\Response
     */
    public function states($country)
    {
        $states = State::select('id', 'name', 'iso_code')
            ->where('country_id', $country)
            ->get();

        return StateResource::collection($states);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return new PageResource($page);
    }
}
