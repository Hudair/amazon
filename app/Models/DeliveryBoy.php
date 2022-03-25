<?php

namespace App\Models;

use App\Common\Imageable;
use App\Common\Addressable;
use App\Common\ApiAuthTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DeliveryBoy extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, ApiAuthTokens, Addressable, Imageable;

    protected $fillable = [
        'shop_id',
        'first_name',
        'last_name',
        'nice_name',
        'email',
        'phone_number',
        'password',
        'status',
        'dob',
        'sex',
        'remember_token',
        'verification_token'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_boys';

    /**
     * The guard used by the model.
     *
     * @var string
     */
    protected $guard = 'delivery_boy';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * This will returs full name.
     *
     * @return [full_name]
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * This method will make hash password.
     *
     * @return [hash_password]
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    /**
     * This function returns a shop_name which associated with delivery boy
     * @return [shop]
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * Get address.
     */
    // public function address() : MorphOne
    // {
    //     return $this->morphOne(Address::class, 'addressable');
    // }

    /**
     * Get name the user.
     *
     * @return mix
     */
    public function getName()
    {
        return $this->nice_name ?? $this->full_name;
    }

    /**
     * Scope a query to only include inactive records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', '==', BaseModel::ACTIVE);
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
