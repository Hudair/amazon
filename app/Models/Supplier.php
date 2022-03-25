<?php

namespace App\Models;

use App\Common\Addressable;
use App\Common\Imageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends BaseModel
{
    use HasFactory, SoftDeletes, Addressable, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'email',
        'contact_person',
        'url',
        'bio',
        'active',
    ];

    /**
     * Get the inventories for the supplier.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the shop for the supplier.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
