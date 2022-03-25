<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_items';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['created_at', 'created_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //                     'order_id',
    //                     'inventory_id',
    //                     'item_description',
    //                     'quantity',
    //                     'unit_price',
    //                     'feedback_id',
    //                 ];

    /**
     * Get the country associated with the order.
     */
    // public function feedback()
    // {
    //        return $this->hasOne(Feedback::class, 'id', 'feedback_id');
    // }
}
