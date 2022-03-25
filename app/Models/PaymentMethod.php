<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class PaymentMethod extends BaseModel
{
    const TYPE_PAYPAL = 1;
    const TYPE_CREDIT_CARD = 2;
    const TYPE_MANUAL = 3;
    const TYPE_OTHERS = 4;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_methods';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'split_money' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company_name',
        'type',
        'split_money',
        'code',
        'website',
        'help_doc_url',
        'admin_help_doc_link',
        'terms_conditions_link',
        'description',
        'instructions',
        'admin_description',
        'enabled',
        'order',
    ];

    /**
     * Get the shops for the inventory.
     */
    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_payment_methods', 'payment_method_id', 'shop_id')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('enabled', 1);
    }

    /**
     * Scope a query to include online payment methods only.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnline($query)
    {
        return $query->where('type', '!=', static::TYPE_MANUAL);
    }

    /**
     * Scope a query to include offline payment methods only.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOffline($query)
    {
        return $query->where('type', static::TYPE_MANUAL);
    }

    /**
     * Return payment method type with details
     *
     * @return array
     */
    // public function type()
    // {
    //     return get_payment_method_type($this->type);
    // }

    /**
     * Getter
     */
    // public function getTypeIdAttribute()
    // {
    //     return $this->type;
    // }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        if ($this->code == 'zcart-wallet') {
            return get_platform_title() . ' ' . $value;
        }

        return $value;
    }

    /**
     * Payment method type string
     *
     * @param  int $type
     *
     * @return string
     */
    public function typeName($type)
    {
        switch ($type) {
            case static::TYPE_PAYPAL:
                return trans('app.payment_method_type.paypal.name');

            case static::TYPE_CREDIT_CARD:
                return trans('app.payment_method_type.credit_card.name');

            case static::TYPE_MANUAL:
                return trans('app.payment_method_type.manual.name');

            default:
                return trans('app.payment_method_type.others.name');
        }
    }
}
