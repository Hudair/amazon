<?php

namespace App\Models;

use App\Common\Addressable;
use App\Common\Billable;
use App\Common\Imageable;
use App\Common\Loggable;
use App\Common\Feedbackable;
use App\Helpers\Statistics;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
// use Incevio\Package\Wallet\Traits\HasWallet;
use Carbon\Carbon;

class Shop extends BaseModel
{
    // use HasWallet;

    use HasFactory, SoftDeletes, Loggable, Notifiable, Addressable, Imageable, Feedbackable, Billable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shops';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'active' => 'boolean',
        'hide_trial_notice' => 'boolean',
        'payment_verified' => 'boolean',
        'id_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'address_verified' => 'boolean',
    ];

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var tring
     */
    protected static $logName = 'shop';

    /**
     * Record events only
     *
     * @var array
     */
    protected static $recordEvents = ['updated'];

    /**
     * The name that will be ignored when log this model.
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'stripe_id',
        'card_brand',
        'card_holder_name',
        'trial_ends_at',
        'hide_trial_notice',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'name',
        'legal_name',
        'email',
        'slug',
        'description',
        'external_url',
        'timezone_id',
        'current_billing_plan',
        'stripe_id',
        'card_holder_name',
        'card_brand',
        'card_last_four',
        'trial_ends_at',
        'custom_subscription_fee',
        'commission_rate',
        'hide_trial_notice',
        'active',
        'payment_verified',
        'id_verified',
        'phone_verified',
        'address_verified',
    ];

    /**
     * Get the user that owns the shop.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id')->withTrashed();
    }

    /**
     * Get the staffs for the shop.
     */
    public function staffs()
    {
        return $this->hasMany(User::class)->withTrashed();
    }

    /**
     * Get the delivery boys for the shop.
     */
    public function deliveryBoys()
    {
        return $this->hasMany(DeliveryBoy::class)->withTrashed();
    }

    /**
     * Get the config for the shop.
     */
    public function config()
    {
        return $this->hasOne(Config::class);
    }

    /**
     * Get current subscription plan of the shop.
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'current_billing_plan', 'plan_id')
            ->withDefault();
    }

    /**
     * Get the warehouses for the product.
     */
    // public function address()
    // {
    //     return $this->morphOne(Address::class, 'addressable');
    // }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    /**
     * Get the ShippingZones for the shop.
     */
    public function shippingZones()
    {
        return $this->hasMany(ShippingZone::class);
    }

    /**
     * Get the ShippingZones for the shop.
     */
    // public function shipping_zones()
    // {
    //     return $this->hasMany(ShippingZone::class);
    // }

    /**
     * Get the carriers for the shop.
     */
    public function carriers()
    {
        return $this->hasMany(Carrier::class);
    }

    /**
     * Get the products for the shop.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the inventories for the shop.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the orders for the shop.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get sold items count.
     *
     * @return int
     */
    public function soldItemsCount()
    {
        return $this->orders->sum('pivot.quantity');
    }

    // public function top_listings()
    // {
    //     return $this->inventories->sum('pivot.quantity');

    //     return $this->hasManyThrough(
    //         Inventory::class,
    //         Order::class,
    //         'country_id', // Foreign key on users table...
    //         'user_id', // Foreign key on posts table...
    //         'id', // Local key on countries table...
    //         'id' // Local key on users table...
    //     );

    //     // return $this->belongsToMany(Inventory::class, 'order_items', 'inventory_id', 'order_id')
    //     //     ->selectRaw('parts.*, sum(project_part.count) as pivot_count')
    //     //     ->withTimestamps()
    //     //     ->groupBy('project_part.pivot_part_id');
    // }

    /**
     * Get the carts for the shop.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the coupons for the customer.
     */
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class)->withTimestamps();
    }

    /**
     * Get the user gift_cards.
     */
    public function gift_cards()
    {
        return $this->hasMany(GiftCard::class);
    }

    /**
     * Get the paymentMethods for the shop.
     */
    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'shop_payment_methods', 'shop_id', 'payment_method_id')
            ->orderBy('order')->withTimestamps();
    }

    /**
     * Get the taxes for the shop.
     */
    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    /**
     * Get the suppliers for the product.
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    /**
     * Get the packagings for the product.
     */
    public function packagings()
    {
        return $this->hasMany(Packaging::class);
    }

    /**
     * Get the activePackagings for the product.
     */
    public function activePackagings()
    {
        return $this->hasMany(Packaging::class)->where('active', 1);
    }

    /**
     * Get the defaultPackaging for the product.
     */
    public function defaultPackaging()
    {
        return $this->hasOne(Packaging::class)->where('default', 1)->withDefault();
    }

    public function revenue()
    {
        return $this->hasMany(Order::class)
            ->selectRaw('SUM(total) as total, shop_id')
            ->groupBy('shop_id');
    }

    /**
     * Calculate the lifetime value of the vendor
     */
    public function getLifetimeValueAttribute()
    {
        $amount = 0;

        if ($revenue = $this->revenue->first()) {
            $amount = $revenue['total'] ? round($revenue['total']) : $amount;
        }

        return get_formated_currency($amount);
    }

    // /**
    //  * Get the stripe for the shop.
    //  */
    // public function stripe()
    // {
    //     return $this->hasOne(ConfigStripe::class, 'shop_id')->withDefault();
    // }

    // /**
    //  * Get the authorizeNet for the shop.
    //  */
    // public function authorizeNet()
    // {
    //     return $this->hasOne(ConfigAuthorizeNet::class, 'shop_id')->withDefault();
    // }

    /**
     * Get the paypalExpress for the shop.
     */
    public function paypalExpress()
    {
        return $this->hasOne(ConfigPaypalExpress::class, 'shop_id')->withDefault();
    }

    // /**
    //  * Get the instamojo for the shop.
    //  */
    // public function instamojo()
    // {
    //     return $this->hasOne(ConfigInstamojo::class, 'shop_id')->withDefault();
    // }

    // /**
    //  * Get the paystack for the shop.
    //  */
    // public function paystack()
    // {
    //     return $this->hasOne(ConfigPaystack::class, 'shop_id')->withDefault();
    // }

    /**
     * Get the manualPaymentMethods for the shop.
     */
    // public function manualPaymentMethods()
    // {
    //     return $this->belongsToMany(PaymentMethod::class, 'config_manual_payments', 'shop_id', 'payment_method_id')
    //     ->withPivot('additional_details', 'payment_instructions')->withTimestamps();
    // }

    /**
     * Get the timezone the shop.
     */
    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function openTickets()
    {
        return $this->tickets()->where('status', '<', Ticket::STATUS_SOLVED);
    }

    public function solvedTickets()
    {
        return $this->tickets()->where('status', '=', Ticket::STATUS_SOLVED);
    }

    public function closedTickets()
    {
        return $this->tickets()->where('status', '=', Ticket::STATUS_CLOSED);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    public function openDisputes()
    {
        return $this->disputes()->where('status', '<', Dispute::STATUS_SOLVED);
    }

    public function solvedDisputes()
    {
        return $this->disputes()->where('status', '=', Dispute::STATUS_SOLVED);
    }

    public function closedDisputes()
    {
        return $this->disputes()->where('status', '=', Dispute::STATUS_CLOSED);
    }

    /**
     * Check if the current subscription plan allow to add more user
     *
     * @return bool
     */
    public function canAddMoreUser()
    {
        if (!is_subscription_enabled()) {
            return true;
        }

        if ($this->current_billing_plan) {
            $plan = SubscriptionPlan::findOrFail($this->current_billing_plan);

            if (Statistics::shop_user_count() < $plan->team_size) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the current subscription plan allow to add more Inventory
     *
     * @return bool
     */
    public function canAddMoreInventory()
    {
        if (!is_subscription_enabled()) {
            return true;
        }

        if ($this->current_billing_plan) {
            $plan = SubscriptionPlan::findOrFail($this->current_billing_plan);

            if (Statistics::shop_inventories_count() < $plan->inventory_limit) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the vandor can creat listing using this product
     *
     * @return bool
     */
    public function canAddThisInventory($product)
    {
        if ($this->canUseSystemCatalog()) {
            return true;
        }

        if (!$product instanceof Product && !is_object($product)) {
            $product = Product::select('shop_id')->where('id', $product)->first();
        }

        if (isset($product->shop_id)) {
            return $product->shop_id == $this->id;
        }

        return false;
    }

    /**
     * Check if shop can use common catalog or just its own
     *
     * @return bool
     */
    public function canUseSystemCatalog()
    {
        return !(bool) config('system_settings.can_use_own_catalog_only');
    }

    public function setHideTrialNoticeAttribute($value)
    {
        $this->attributes['hide_trial_notice'] = (bool) $value;
    }

    /**
     * Scope a query to only include active shops.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope a query to only include active shops.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        // return $query;

        $query = $query->approved()
            ->whereHas('addresses')
            ->whereHas('config', function ($q) {
                $q->live();
                $q->activeEcommerce();
            });

        // Vendor has to configure payment method when get paid directly
        if (vendor_get_paid_directly()) {
            return $query->whereHas('paymentMethods');
        }

        if (!is_subscription_enabled()) {
            return $query;
        }

        $query = $query->where(function ($q) {
            $q->whereNotNull('current_billing_plan')
                ->where(
                    function ($x) {
                        $x->doesntHave('subscriptions')
                            ->whereNull('trial_ends_at')
                            ->orWhere('trial_ends_at', '>', Carbon::now());
                    }
                )
                ->orWhere(
                    function ($r) {
                        $r->whereHas('subscriptions', function ($s) {
                            $s->whereNested(function ($t) {
                                $t->whereNull('ends_at')
                                    ->orWhere('ends_at', '>', Carbon::now())
                                    ->orWhereNotNull('trial_ends_at')
                                    ->where('trial_ends_at', '>', Carbon::today());
                            });
                        });
                    }
                );
        });

        return $query;
    }

    /**
     * [Return the Subscription Renew Date /Next billing date
     *
     * @return string
     */
    public function getNextBillingDate()
    {
        if ($this->onGenericTrial()) {
            return trans('app.on_generic_trial');
        }

        if (!$this->subscribed($this->current_billing_plan)) {
            return trans('app.on_generic_trial');
        }

        $sub = $this->subscription($this->current_billing_plan)->asStripeSubscription();

        return Carbon::createFromTimeStamp($sub->current_period_end)->toFormattedDateString();
    }

    public function getVerificationStatus()
    {
        if ($this->id_verified && $this->phone_verified && $this->address_verified) {
            return trans('app.verified');
        } elseif ($this->id_verified || $this->phone_verified || $this->address_verified) {
            return trans('app.partially_verified');
        }

        return trans('app.not_verified');
    }

    public function getVerifiedAttribute()
    {
        return (bool) $this->isVerified();
    }

    public function isVerified()
    {
        return $this->id_verified && $this->phone_verified && $this->address_verified;
    }

    /**
     * Set the id_verified for the Product.
     */
    public function setIdVerifiedAttribute($value)
    {
        $this->attributes['id_verified'] = (bool) $value;
    }

    /**
     * Set the address_verified for the Product.
     */
    public function setAddressVerifiedAttribute($value)
    {
        $this->attributes['address_verified'] = (bool) $value;
    }

    /**
     * Set the phone_verified for the Product.
     */
    public function setPhoneVerifiedAttribute($value)
    {
        $this->attributes['phone_verified'] = (bool) $value;
    }

    /**
     * Set the active for the Product.
     */
    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = (bool) $value;
    }

    /**
     * Activities for the loggable model
     *
     * @return [type] [description]
     */
    public function logs()
    {
        return $this->activities()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Check if shop has active payment method.
     *
     * @return bool
     */
    public function hasPaymentMethods()
    {
        return DB::table('shop_payment_methods')->where('shop_id', $this->id)->first();

        // return (bool) $this->paymentMethods()->count();
    }

    /**
     * Check if shop has Shipping Zones.
     *
     * @return bool
     */
    public function hasShippingZones()
    {
        return (bool) $this->shippingZones()->active()->count();
    }

    /**
     * Check if the shop is has billing token
     *
     * @return bool
     */
    public function hasBillingToken()
    {
        return $this->hasStripeId();
    }

    /**
     * Check if the user has outrange plan
     *
     * @return bool
     */
    public function hasExpiredPlan()
    {
        if ($subscription = $this->currentSubscription) {
            return $subscription->ends_at && $subscription->ends_at->isPast();
        }

        return null;
    }

    /**
     * Check if the system is down or live.
     *
     * @return bool
     */
    public function isDown()
    {
        return $this->config->maintenance_mode;
    }

    /**
     * Check if the vendor has eerything setup
     *
     * @return bool
     */
    public function canGoLive()
    {
        $result = !$this->isDown() && $this->isActive();
        // $result = !$this->isDown() && $this->isActive() && $this->address;

        // Vendor has to configure payment method when get paid directly
        if (vendor_get_paid_directly()) {
            $result = $this->hasPaymentMethods();
        }

        if (!is_subscription_enabled()) {
            return $result;
        }

        return $result && $this->onGenericTrial() || $this->hasActiveSubscription();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getQualifiedName()
    {
        $badge = '';

        if ($this->isVerified()) {
            $badge = '<img src="' . get_verified_badge() . '" class="verified-badge img-tiny" data-toggle="tooltip" data-placement="top" title="' . trans('help.verified_seller') . '" alt="verified-badge">';
        }

        return $this->name . $badge;
    }

    public function verifiedText()
    {
        return $this->isVerified() ? trans('help.verified_seller') : '';
    }

    /** if package zipcode active this function will return zipcode query
     * @param $query
     * @return object
     */
    public function scopeZipcode($query): object
    {
        if (is_incevio_package_loaded('zipcode')) {
            return $query->whereHas('address', function ($builder) {
                return $builder->where('zip_code', session('zipcode_default'));
            });
        }

        return $query;
    }
}
