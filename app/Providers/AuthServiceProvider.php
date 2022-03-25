<?php

namespace App\Providers;

use App\Models\DeliveryBoy;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Attachment::class          => \App\Policies\AttachmentPolicy::class,
        \App\Models\Attribute::class           => \App\Policies\AttributePolicy::class,
        \App\Models\AttributeValue::class      => \App\Policies\AttributeValuePolicy::class,
        \App\Models\Banner::class              => \App\Policies\BannerPolicy::class,
        \App\Models\Blog::class                => \App\Policies\BlogPolicy::class,
        // \App\CarrierValue::class        => \App\Policies\CarrierValuePolicy::class,
        \App\Models\Cart::class                => \App\Policies\CartPolicy::class,
        \App\Models\Carrier::class             => \App\Policies\CarrierPolicy::class,
        \App\Models\Category::class            => \App\Policies\CategoryPolicy::class,
        \App\Models\CategoryGroup::class       => \App\Policies\CategoryGroupPolicy::class,
        \App\Models\CategorySubGroup::class    => \App\Policies\CategorySubGroupPolicy::class,
        \App\Models\ChatConversation::class    => \App\Policies\ChatConversationPolicy::class,
        \App\Models\Config::class              => \App\Policies\ConfigPolicy::class,
        \App\Models\Coupon::class              => \App\Policies\CouponPolicy::class,
        \App\Models\Customer::class            => \App\Policies\CustomerPolicy::class,
        \App\Models\Country::class             => \App\Policies\CountryPolicy::class,
        \App\Models\Currency::class            => \App\Policies\CurrencyPolicy::class,
        \App\Models\Dispute::class             => \App\Policies\DisputePolicy::class,
        \App\Models\EmailTemplate::class       => \App\Policies\EmailTemplatePolicy::class,
        \App\Models\Faq::class                 => \App\Policies\FaqPolicy::class,
        \App\Models\GiftCard::class            => \App\Policies\GiftCardPolicy::class,
        \App\Models\Inventory::class           => \App\Policies\InventoryPolicy::class,
        \App\Models\Language::class            => \App\Policies\LanguagePolicy::class,
        \App\Models\Manufacturer::class        => \App\Policies\ManufacturerPolicy::class,
        \App\Models\Merchant::class            => \App\Policies\MerchantPolicy::class,
        \App\Models\Message::class             => \App\Policies\MessagePolicy::class,
        \App\Models\Order::class               => \App\Policies\OrderPolicy::class,
        \App\Models\Packaging::class           => \App\Policies\PackagingPolicy::class,
        \App\Models\Page::class                => \App\Policies\PagePolicy::class,
        \App\Models\Permission::class          => \App\Policies\PermissionPolicy::class,
        \App\Models\Product::class             => \App\Policies\ProductPolicy::class,
        \App\Models\Refund::class              => \App\Policies\RefundPolicy::class,
        \App\Models\Role::class                => \App\Policies\RolePolicy::class,
        \App\Models\Shop::class                => \App\Policies\ShopPolicy::class,
        \App\Models\ShippingRate::class        => \App\Policies\ShippingRatePolicy::class,
        \App\Models\ShippingZone::class        => \App\Policies\ShippingZonePolicy::class,
        \App\Models\Slider::class              => \App\Policies\SliderPolicy::class,
        \App\Models\SubscriptionPlan::class    => \App\Policies\SubscriptionPlanPolicy::class,
        \App\Models\Supplier::class            => \App\Policies\SupplierPolicy::class,
        \App\Models\System::class              => \App\Policies\SystemPolicy::class,
        \App\Models\SystemConfig::class        => \App\Policies\SystemConfigPolicy::class,
        \App\Models\Tax::class                 => \App\Policies\TaxPolicy::class,
        \App\Models\Ticket::class              => \App\Policies\TicketPolicy::class,
        \App\Models\User::class                => \App\Policies\UserPolicy::class,
        \App\Models\Warehouse::class           => \App\Policies\WarehousePolicy::class,
        \App\Models\Wishlist::class            => \App\Policies\WishlistPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes();
        }

        // Passport::tokensCan([
        //     'user' => 'User Type',
        //     'customer' => 'Admin User Type',
        //     'delivery_boy' => 'Delivery Boy User Type'
        // ]);

        // Gate::resource('blog', 'BlogPolicy');

        // Gate::resource('posts', 'PostPolicy', [
        //     'restore' => 'restore',
        //     'destroy' => 'destroy',
        // ]);

        
    }
}
