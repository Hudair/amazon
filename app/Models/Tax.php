<?php

namespace App\Models;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends BaseModel
{
    use HasFactory, SoftDeletes;

    const DEFAULT_TAX_ID = 1;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'taxes';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'deleted_at'];

    /**
     * Get the Country associated with the blog post.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the State associated with the blog post.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the Shop associated with the blog post.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // /**
    //  * Get the inventories for the supplier.
    //  */
    // public function inventories()
    // {
    //     return $this->hasMany(Inventory::class);
    // }

    /**
     * Get the carts for the supplier.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the orders for the supplier.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Creat new state and set the id if the given value is not available
     */
    public function setStateIdAttribute($value)
    {
        if (!is_numeric($value) and $value != null) {
            $state = State::create(['name' => $value, 'country_id' => \Request::input('country_id')]);
            $value = $state->id;
        }

        $this->attributes['state_id'] = $value;
    }
}
