<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends BaseModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    protected $casts = [
        'date_invoice' => 'datetime',
        'date_payment' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'order_id',
        'customer_id',
        'total',
        'shipping',
        'discount',
        'tax',
        'grand_total',
        'paid',
        'payment_date',
        'payment_status_id',
        'payment_method_id',
        'payment_date',
    ];

    /**
     * Get the customer associated with the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the shop associated with the invoice.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the tax associated with the cart.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    /**
     * Get the order associated with the cart.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the paymentMethod for the invoice.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Set tag date formate
     */
    public function setPaymentDateAttribute($date)
    {
        $this->attributes['payment_date'] = Carbon::createFromFormat('Y-m-d', $date);
    }
}
