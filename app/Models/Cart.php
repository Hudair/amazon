<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends BaseModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'carts';

    /**
     * Load item count with cart
     *
     * @var array
     */
    protected $withCount = ['inventories'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'customer_id',
        'ip_address',
        'ship_to',
        'ship_to_country_id',
        'ship_to_state_id',
        'shipping_zone_id',
        'shipping_rate_id',
        'packaging_id',
        'taxrate',
        'item_count',
        'quantity',
        'total',
        'discount',
        'shipping',
        'packaging',
        'handling',
        'taxes',
        'grand_total',
        'shipping_weight',
        'shipping_address',
        'billing_address',
        'email',
        'coupon_id',
        'payment_method_id',
        'payment_status',
        'message_to_customer',
        'admin_note',
        'razorpay_order_id',
    ];

    /**
     * Get the country associated with the order.
     */
    public function shipTo()
    {
        return $this->belongsTo(Address::class, 'ship_to');
        // return $this->belongsTo(Country::class, 'ship_to');
    }

    /**
     * Get the country associated with the cart.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'ship_to_country_id')->withDefault([
            'id' => null,
            'name' => geoip(get_visitor_IP())->country,
        ]);
    }

    /**
     * Get the state associated with the cart.
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'ship_to_state_id')->withDefault([
            'id' => null,
            'name' => geoip(get_visitor_IP())->state_name,
        ]);
    }

    /**
     * Get the customer associated with the cart.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault([
            'name' => trans('app.guest_customer'),
        ]);
    }

    /**
     * Get the shop associated with the cart.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class)->withDefault();
    }

    /**
     * Fetch billing address
     *
     * @return Address or null
     */
    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address');
    }

    /**
     * Fetch billing address
     *
     * @return Address or null
     */
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address');
    }

    /**
     * Get the shippingZone for the order.
     */
    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }

    /**
     * Get the shippingRate for the order.
     */
    public function shippingRate()
    {
        return $this->belongsTo(ShippingRate::class, 'shipping_rate_id');
    }

    /**
     * Get the packaging for the order.
     */
    public function shippingPackage()
    {
        return $this->belongsTo(Packaging::class, 'packaging_id');
    }

    /**
     * Get the carrier associated with the cart.
     */
    public function carrier()
    {
        return optional($this->shippingRate)->carrier();
    }

    /**
     * Get the coupon associated with the order.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the inventories for the product.
     */
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'cart_items')
            ->withPivot('item_description', 'quantity', 'unit_price')
            ->withTimestamps();
    }

    /**
     * Get the paymentMethod for the order.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Setters
     */
    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = $value ?? null;
    }

    public function setShippingAddressAttribute($value)
    {
        $this->attributes['shipping_address'] = is_numeric($value) ? $value : null;
    }

    public function setBillingAddressAttribute($value)
    {
        $this->attributes['billing_address'] = is_numeric($value) ? $value : null;
    }

    /**
     * Return shipping cost with handling fee
     *
     * @return number
     */
    public function get_shipping_cost()
    {
        if ($this->shipping_rate_id) {
            return $this->shipping + $this->handling;
        }

        return $this->is_free_shipping() ? 0 : $this->shipping + $this->handling;
    }

    /**
     * Return handling cost
     *
     * @return number
     */
    public function get_handling_cost()
    {
        if ($this->shipping_rate_id) {
            $this->handling = getShopConfig($this->shop_id, 'order_handling_cost');
        }

        return $this->handling ?? 0;
    }

    /**
     * Return grand tolal
     *
     * @return number
     */
    public function calculate_grand_total()
    {
        $grand_total = ($this->total + $this->taxes + $this->packaging) - $this->discount;

        if ($this->shipping_rate_id) {
            $grand_total = $grand_total + $this->shipping + $this->handling;
        }

        return $grand_total;
    }

    /**
     * Check if the cart eligable for free shipping
     *
     * @return bool
     */
    public function is_free_shipping()
    {
        foreach ($this->inventories as $item) {
            if (!$item->free_shipping) {
                return false;
            }
        }

        return true;
    }

    public function get_tax_amount()
    {
        if ($this->taxrate && $this->taxrate > 0) {
            return $this->total * ($this->taxrate / 100);
        }

        return 0;
    }

    /* Calculate and return the discount amount */
    public function get_discounted_amount()
    {
        if (!$this->coupon_id) {
            return 0;
        }

        // Check if the coupon is still valid for the cart
        if (!$this->coupon->isValidForTheCart($this)) {
            $this->coupon_id = null;
            $this->discount = 0;
            $this->grand_total = $this->calculate_grand_total();
            $this->save();
        }

        if ('percent' == $this->coupon->type) {
            return $this->coupon->value * ($this->total / 100);
        }

        // When the coupon value is bigger than cart total
        if ($this->total < $this->coupon->value) {
            return $this->total;
        }

        return $this->coupon->value;
    }

    public function getLabelText()
    {
        $txt = '';
        if ($this->coupon_id && $this->discount) {
            $txt .= trans('app.coupon_applied', ['coupon' => $this->coupon->name]);
            // $txt .= trans('app.discount_applied', ['amount' => get_formated_currency($this->discount)]);
        }

        return $txt;
    }
}
