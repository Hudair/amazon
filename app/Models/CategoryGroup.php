<?php

namespace App\Models;

use App\Common\CascadeSoftDeletes;
use App\Common\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryGroup extends BaseModel
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'slug', 'icon', 'order', 'active', 'meta_title', 'meta_description'];

    /**
     * Cascade Soft Deletes Relationships
     *
     * @var array
     */
    protected $cascadeDeletes = ['subGroups'];

    /**
     * Get the subGroups associated with the CategoryGroup.
     */
    public function subGroups()
    {
        return $this->hasMany(CategorySubGroup::class, 'category_group_id')->orderBy('order', 'asc');
    }

    /**
     * Get the categories associated with the CategoryGroup.
     */
    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            CategorySubGroup::class,
            'category_group_id', // Foreign key on CategorySubGroup table...
            'category_sub_group_id', // Foreign key on Category table...
            'id', // Local key on CategoryGroup table...
            'id' // Local key on CategorySubGroup table...
        );
    }

    /**
     * Setters
     */
    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = $value ?: 100;
    }
}
