<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends BaseModel
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get all of the customers that are assigned this tag.
     */
    public function customers()
    {
        return $this->morphedByMany(App\Models\Customer::class, 'taggable');
    }

    /**
     * Get all of the shops that are assigned this tag.
     */
    public function shops()
    {
        return $this->morphedByMany(App\Models\Shop::class, 'taggable');
    }

    /**
     * Get all of the suppliers that are assigned this tag.
     */
    public function suppliers()
    {
        return $this->morphedByMany(App\Models\Supplier::class, 'taggable');
    }

    /**
     * Get all of the products that are assigned this tag.
     */
    public function products()
    {
        return $this->morphedByMany(App\Models\Product::class, 'taggable');
    }

    /**
     * Get all of the manufacturers that are assigned this tag.
     */
    public function manufacturers()
    {
        return $this->morphedByMany(App\Models\Manufacturer::class, 'taggable');
    }

    /**
     * Get all of the blogs that are assigned this tag.
     */
    public function blogs()
    {
        return $this->morphedByMany(App\Models\Blog::class, 'taggable');
    }
}
