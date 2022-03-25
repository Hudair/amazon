<?php

namespace App\Models;

use App\Common\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AttributeValue extends BaseModel
{
    use HasFactory, SoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_values';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'value',
        'color',
        'attribute_id',
        'order',
    ];

    /**
     * Get the attribute for the AttributeValue.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the inventories for the supplier.
     */
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'attribute_inventory')
            ->withPivot('attribute_id')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include records from the users shop.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMine($query)
    {
        return $query->where('shop_id', Auth::user()->merchantId());
    }
}
