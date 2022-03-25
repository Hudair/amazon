<?php

namespace App\Models;

use App\Common\Loggable;
use App\Events\Order\OrderCancellationRequestDeclined;

class Cancellation extends BaseModel
{
    use Loggable;

    const STATUS_NEW = 1;         //Default
    const STATUS_OPEN = 2;
    const STATUS_APPROVED = 3;
    const STATUS_DECLINED = 4;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cancellations';

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var bool
     */
    protected static $logName = 'cancellation';

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'return_goods' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'cancellation_reason_id',
        'customer_id',
        'order_id',
        'items',
        'description',
        'return_goods',
        'status',
    ];

    /**
     * Get the shop associated with the model.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class)->withDefault();
    }

    /**
     * Get the customer associated with the model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function cancellation_reason()
    {
        return $this->belongsTo(CancellationReason::class, 'cancellation_reason_id');
    }

    /**
     * Get the order associated with the model.
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    /**
     * Setters
     */
    public function setItemsAttribute($value)
    {
        $this->attributes['items'] = $value ? serialize($value) : null;
    }

    /**
     * Getters
     */
    public function getReasonAttribute()
    {
        return optional($this->cancellation_reason)->detail;
    }

    public function getItemsAttribute($value)
    {
        return $value ? unserialize($value) : null;
    }

    public function getItemsCountAttribute($value)
    {
        return is_array($this->items) ? count($this->items) : $this->order->quantity;
    }

    public function approve()
    {
        $this->forceFill(['status' => static::STATUS_APPROVED])->save();

        $this->order->cancel($this->isPartial());
    }

    public function decline()
    {
        $this->forceFill(['status' => static::STATUS_DECLINED])->save();

        event(new OrderCancellationRequestDeclined($this->order));
    }

    public function resetStatus()
    {
        $this->forceFill(['status' => static::STATUS_NEW])->save();
    }

    /**
     * Scope a query to only include approved cancellations.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', static::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include records from the users shop.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('status', '<', static::STATUS_APPROVED);
    }

    /**
     * Request type
     */
    public function getRequestTypeAttribute()
    {
        return $this->return_goods ? 'return' : 'cancellation';
    }

    /**
     * Check if the given item id is in the request
     */
    public function isItemInRequest($item)
    {
        return $this->items && in_array($item, $this->items);
    }

    /**
     * Return true if the request is Open
     */
    public function isOpen()
    {
        return $this->status < static::STATUS_APPROVED;
    }

    public function isNew()
    {
        return $this->status == static::STATUS_NEW;
    }

    public function inReview()
    {
        return $this->status == static::STATUS_OPEN;
    }

    public function isClosed()
    {
        return $this->status >= static::STATUS_APPROVED;
    }

    public function isApproved()
    {
        return $this->status == static::STATUS_APPROVED;
    }

    public function isDeclined()
    {
        return $this->status >= static::STATUS_DECLINED;
    }

    public function isPartial()
    {
        return $this->items_count < $this->order->quantity;
    }

    public function statusName($plain = false)
    {
        $status = strtoupper(get_cancellation_reason_txt($this->status));

        if ($plain) {
            return $status;
        }

        switch ($this->status) {
            case static::STATUS_NEW:
                return '<span class="label label-outline">' . $status . '</span>';
            case static::STATUS_OPEN:
                return '<span class="label label-default">' . $status . '</span>';
            case static::STATUS_APPROVED:
                return '<span class="label label-primary">' . $status . '</span>';
            case static::STATUS_DECLINED:
                return '<span class="label label-danger">' . $status . '</span>';
        }
    }
}
