<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class ShippingZone extends BaseModel
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shipping_zones';

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'rest_of_the_world' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'tax_id',
        'country_ids',
        'state_ids',
        'rest_of_the_world',
        'active',
    ];

    /**
     * Get the shop for the inventory.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the tax associated with the order.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class)->withDefault();
    }

    /**
     * Get the rates for the order.
     */
    public function rates()
    {
        return $this->hasMany(ShippingRate::class);
    }

    /**
     * Setters
     */
    public function setRestOfTheWorldAttribute($value)
    {
        $this->attributes['rest_of_the_world'] = (bool) $value;
    }

    public function setCountryIdsAttribute($value)
    {
        $this->attributes['country_ids'] = serialize($value);
    }

    public function setStateIdsAttribute($value)
    {
        $this->attributes['state_ids'] = serialize($value);
    }

    /**
     * Getters
     */
    public function getCountryIdsAttribute($value)
    {
        return unserialize($value);
    }

    public function getStateIdsAttribute($value)
    {
        return unserialize($value);
    }
}
