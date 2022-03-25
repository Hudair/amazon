<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Attribute extends BaseModel
{
    use SoftDeletes;

    const TYPE_COLOR = 1;         //Color/Pattern
    const TYPE_RADIO = 2;
    const TYPE_SELECT = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'attribute_type_id',
        'order',
    ];

    /**
     * Get the Shop associated with the attribute.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the AttributeType for the Attribute.
     */
    public function attributeType()
    {
        return $this->belongsTo(AttributeType::class);
    }

    /**
     * Attribute has many AttributeValue
     */
    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class)->orderBy('order', 'asc');
    }

    /**
     * Get the inventories for the Attribute.
     */
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'attribute_inventory')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }

    /**
     * Get the categories for the attributes.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'attribute_categories')->withTimestamps();
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

    /**
     * Return extra classes to views based on type
     *
     * @return string
     */
    public function getCssClassesAttribute()
    {
        switch ($this->attribute_type_id) {
            case static::TYPE_COLOR:
                return 'color-options';
            case static::TYPE_RADIO:
                return 'radioSelect';
            case static::TYPE_SELECT:
                return 'selectBoxIt';
            default:
                return 'selectBoxIt';
        }
    }
}
