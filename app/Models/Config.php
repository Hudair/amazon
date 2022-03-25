<?php

namespace App\Models;

use App\Common\Attachable;
// use App\Common\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Config extends BaseModel
{
    use HasFactory, Attachable;
    // use HasFactory, Attachable, Loggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'configs';

    /**
     * The database primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'shop_id';

    /**
     * The primanry key is not incrementing
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'pending_verification' => 'boolean',
        'auto_archive_order' => 'boolean',
        'digital_goods_only' => 'boolean',
        'notify_new_disput' => 'boolean',
        'notify_new_message' => 'boolean',
        'notify_alert_quantity' => 'boolean',
        'notify_inventory_out' => 'boolean',
        'notify_new_order' => 'boolean',
        'notify_abandoned_checkout' => 'boolean',
        'enable_live_chat' => 'boolean',
        'notify_new_chat' => 'boolean',
    ];

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var bool
     */
    // protected static $logName = 'config';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the shop.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get support agent
     */
    public function supportAgent()
    {
        return $this->belongsTo(User::class, 'support_agent');
    }

    /**
     * Get the tax.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'default_tax_id');
    }

    /**
     * Get the default payment method.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'default_payment_method_id');
    }

    /**
     * Get the paymentMethods for the shop.
     */
    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'shop_payment_methods', 'shop_id', 'payment_method_id')
            ->withTimestamps();
    }

    /**
     * Get the manualPaymentMethods for the shop.
     */
    public function manualPaymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'config_manual_payments', 'shop_id', 'payment_method_id')
            ->active()
            ->withPivot('additional_details', 'payment_instructions')
            ->withTimestamps();
    }

    /**
     * Get the stripe for the shop.
     */
    public function stripe()
    {
        return $this->hasOne(ConfigStripe::class, 'shop_id');
    }

    /**
     * Get the authorizeNet for the shop.
     */
    public function authorizeNet()
    {
        return $this->hasOne(ConfigAuthorizeNet::class, 'shop_id');
    }

    /**
     * Get the paypalExpress for the shop.
     */
    public function paypalExpress()
    {
        return $this->hasOne(ConfigPaypalExpress::class, 'shop_id');
    }

    /**
     * Get the instamojo for the shop.
     */
    public function instamojo()
    {
        return $this->hasOne(ConfigInstamojo::class, 'shop_id');
    }

    /**
     * Get the paystack for the shop.
     */
    public function paystack()
    {
        return $this->hasOne(ConfigPaystack::class, 'shop_id');
    }

    /**
     * Get the cybersource for the shop.
     */
    public function cybersource()
    {
        return $this->hasOne(ConfigCyberSource::class, 'shop_id');
    }

    /**
     * Get the Razorpay for the shop.
     */
    public function razorpay()
    {
        return $this->hasOne(\Incevio\Package\Razorpay\Models\ConfigRazorpay::class, 'shop_id');
    }

    /**
     * Get the paypal Marketplace for the shop.
     */
    public function paypalMarketplace()
    {
        return $this->hasOne(\Incevio\Package\PaypalMarketplace\Models\ConfigPaypalMarketplace::class, 'shop_id');
    }

    /**
     * Get the mpesa for the shop.
     */
    public function mpesa()
    {
        return $this->hasOne(\Incevio\Package\MPesa\Models\ConfigMPesa::class, 'shop_id');
    }

    /**
     * Get the supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'default_supplier_id');
    }

    /**
     * Get the warehouse.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'default_warehouse_id');
    }

    /**
     * Setters
     */
    public function setDefaultPackagingIdsAttribute($value)
    {
        $this->attributes['default_packaging_ids'] = serialize($value);
    }

    /**
     * Getters
     */
    public function getDefaultPackagingIdsAttribute($value)
    {
        return unserialize($value);
    }

    /**
     * Check if Chat enabled.
     *
     * @return bool
     */
    public function isChatEnabled()
    {
        return $this->enable_live_chat;
    }

    /**
     * Scope a query to only include active shops.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLive($query)
    {
        return $query->where('maintenance_mode', '!=', 1);
    }

    /**
     * Scope a query to only include active shops.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveEcommerce($query)
    {
        return $query->where('active_ecommerce', 1);
    }

    /**
     * Scope a query to only include shops thats are down for maintainance.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDown($query)
    {
        return $query->where('maintenance_mode', 1);
    }
}
