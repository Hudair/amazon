<?php

namespace App\Models;

use App\Common\CascadeSoftDeletes;
use App\Common\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategorySubGroup extends BaseModel
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category_sub_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'category_group_id', 'slug', 'description', 'active', 'order', 'meta_title', 'meta_description'];

    /**
     * Cascade Soft Deletes Relationships
     *
     * @var array
     */
    protected $cascadeDeletes = ['categories'];

    /**
     * Get the categoryGroup that owns the SubGroup.
     */
    public function group()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id')->withTrashed();
    }

    /**
     * Get the categories for the CategorySubGroup.
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'category_sub_group_id')->orderBy('order', 'asc');
    }

    // /**
    //  * Get all listings for the category.
    //  */
    // public function getListingsAttribute()
    // {
    //     return \DB::table('inventories')
    //     ->join('category_product', 'inventories.product_id', '=', 'category_product.product_id')
    //     ->select('inventories.*', 'category_product.category_id')
    //     ->where('category_product.category_id', '=', $this->id)->get();

    //     // return $this->belongsToMany(Inventory::class, 'category_product', null, 'product_id', null, 'product_id');
    // }

    /**
     * Setters
     */
    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = $value ?: 100;
    }
}
