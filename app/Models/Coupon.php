<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Coupon extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'starting_time' => 'datetime',
        'ending_time' => 'datetime',
        // 'partial_use'           => 'boolean',
        // 'exclude_offer_items'   => 'boolean',
        // 'limited'               => 'boolean',
        // 'exclude_tax_n_shipping' => 'boolean',
        'active'                 => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'code',
        'description',
        'value',
        'min_order_amount',
        'type',
        'quantity',
        'quantity_per_customer',
        'starting_time',
        'ending_time',
        // 'partial_use',
        // 'limited',
        // 'exclude_offer_items',
        // 'exclude_tax_n_shipping',
        'active',
    ];

    /**
     * Get the Shop associated with the Coupon.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the customers for the coupon.
     */
    public function customers()
    {
        return $this->belongsToMany(Customer::class)->withTimestamps();
    }

    /**
     * Get the shippingZones for the coupon.
     */
    public function shippingZones()
    {
        return $this->belongsToMany(ShippingZone::class)->withTimestamps();
    }

    /**
     * Get the orders used the coupon.
     */
    public function orders()
    {
        return $this->hasMany(Order::class)->withTrashed();
    }

    /**
     * Get the user orders.
     */
    public function customerOrders($customer = null)
    {
        $customer = $this->getLoggedinCustomerId($customer);

        return $this->hasMany(Order::class)->where('customer_id', $customer)->withTrashed();
    }

    /**
     * Check if the coupon is Live or in valid period
     *
     * @return bool
     */
    public function isLive()
    {
        return $this->starting_time < Carbon::now() && $this->ending_time > Carbon::now();
    }

    /**
     * Check if the coupon is Limited
     *
     * @return bool
     */
    public function isLimited()
    {
        return $this->forLimitedCustomer() || $this->forLimitedZone();
    }

    /**
     * Check if the coupon is Limited Customers
     *
     * @return bool
     */
    public function forLimitedCustomer()
    {
        return $this->customers->isNotEmpty();
    }

    /**
     * Check if the coupon is Limited Shipping zone
     *
     * @return bool
     */
    public function forLimitedZone()
    {
        return $this->shippingZones->isNotEmpty();
    }

    /**
     * Check if the coupon is Usable
     *
     * @return bool
     */
    public function isValidCustomer($customer = null)
    {
        if (!$this->forLimitedCustomer()) {
            return true;
        }

        $customer = $this->getLoggedinCustomerId($customer);

        return in_array($customer, $this->customers->pluck('id')->toArray());
    }

    /**
     * Check if the coupon is Usable
     *
     * @return bool
     */
    public function isValidZone($zone = null)
    {
        if (!$this->forLimitedZone()) {
            return true;
        }

        return $zone && in_array($zone, $this->shippingZones->pluck('id')->toArray());
    }

    /**
     * Check if the coupon ihasQtt
     *
     * @return bool
     */
    public function hasQtt()
    {
        $count = $this->orders_count ?? $this->orders()->count();
        $customer_count = $this->customer_orders_count ?? $this->customerOrders()->count();

        return $this->quantity > $this->count && $this->quantity_per_customer > $customer_count;
    }

    /**
     * Check if the coupon is Active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active == 1;
    }

    /**
     * Check if the coupon has a minimum order amount value
     *
     * @return bool
     */
    public function passMinAmount($total)
    {
        return $this->min_order_amount == null || $total >= $this->min_order_amount;
    }

    /**
     * Check if the coupon is Active
     *
     * @return bool
     */
    public function isValidForTheCart(Cart $cart)
    {
        // $customer = $this->getLoggedinCustomerId($customer);

        return $this->passMinAmount($cart->total) &&
            $this->isActive() && $this->isLive() &&
            $this->isValidCustomer($cart->customer_id) &&
            $this->isValidZone($cart->shipping_zone_id) &&
            $this->hasQtt();
    }

    /**
     * Setters
     */
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = $value > 1 ? $value : 1;
    }

    public function setQuantityPerCustomerAttribute($value)
    {
        $this->attributes['quantity_per_customer'] = $value > 1 ? $value : 1;
    }

    public function setStartingTimeAttribute($value)
    {
        if ($value) {
            $this->attributes['starting_time'] = Carbon::createFromFormat('Y-m-d h:i a', $value);
        }
    }

    public function setEndingTimeAttribute($value)
    {
        if ($value) {
            $this->attributes['ending_time'] = Carbon::createFromFormat('Y-m-d h:i a', $value);
        }
    }

    /**
     * Getters
     */
    public function getCustomerListAttribute()
    {
        if (count($this->customers)) {
            return $this->customers->pluck('id')->toArray();
        }
    }

    public function getZoneListAttribute()
    {
        if (count($this->shippingZones)) {
            return $this->shippingZones->pluck('id')->toArray();
        }
    }

    /**
     * Scope a query to only include active records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope a query to only include valid records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('active', 1)->where('starting_time', '<', Carbon::now())
            ->where('ending_time', '>', Carbon::now());
    }

    public function getFormatedAmountText()
    {
        return $this->type == 'amount' ?
            get_formated_currency($this->value) :
            get_formated_decimal($this->value) . '%';
    }

    public function validityText($plain = false)
    {
        $now = Carbon::now();
        $ending_time = $this->ending_time->format('M j, g:i a');
        $starting_time = $this->starting_time->format('M j, g:i a');

        if ($this->ending_time < $now) {
            return $plain ? trans('theme.expired_at') . ': ' . $ending_time :
                '<span class="text-muted small">' . trans('theme.expired_at') . ': '
                . $ending_time . '</span>';
        }

        if ($this->starting_time < $now) {
            return $plain ?
                trans('theme.use_before') . ': ' . $ending_time :
                '<span class="text-muted small">' . trans('theme.use_before') . ':' . '</span> '
                . $ending_time;
        }

        if ($this->starting_time > $now) {
            return $plain ?
                trans('theme.use_between') . ': ' . $starting_time . ' ' . trans('theme.and') . ' ' . $ending_time :
                '<span class="text-muted small">'
                . trans('theme.use_between') . ':' . '</span> '
                . $starting_time . '<br/>'
                . '<span class="text-muted small">' . trans('theme.and') . '</span> '
                . $ending_time;
        }

        return $plain ? trans('theme.invalid') : '<span class="text-muted small">' . trans('theme.invalid') . '</span>';
    }

    private function getLoggedinCustomerId($customer = null)
    {
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user()->id;
        } elseif (Auth::guard('api')->check()) {
            $customer = Auth::guard('api')->user()->id;
        }

        return $customer;
    }
}
