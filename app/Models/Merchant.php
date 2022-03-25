<?php

namespace App\Models;

use App\Common\Addressable;
use App\Common\Imageable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Merchant extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, Addressable, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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

    protected $casts = [
        'last_visited_at' => 'datetime',
    ];

    /**
     * The attributes that will be logged on activity logger.
     *
     * @var bool
     */
    protected static $logFillable = true;

    /**
     * The only attributes that has been changed.
     *
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var bool
     */
    protected static $logName = 'merchant';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('merchant', function (Builder $builder) {
            $builder->where('role_id', Role::MERCHANT);
        });
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * Route notifications for the Nexmo channel.
     *
     * @return string
     */
    public function routeNotificationForNexmo()
    {
        return $this->primaryAddress->phone;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'role_id',
        'name',
        'nice_name',
        'email',
        'password',
        'dob',
        'description',
        'sex',
        'active',
        'last_visited_at',
        'last_visited_from',
    ];

    /**
     * Get the dashboard of the merhcant.
     */
    public function dashboard()
    {
        return $this->hasOne(Dashboard::class, 'user_id');
    }

    /**
     * Get all of the country for the merhcant.
     */
    public function country()
    {
        return $this->hasManyThrough(Country::class, Address::class, 'addressable_id', 'country_name');
    }

    /**
     * Get the Roles associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the shop associated with the user.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class)->withDefault();
    }

    /**
     * Get the shops the user own.
     */
    public function owns()
    {
        return $this->hasOne(Shop::class, 'owner_id')->withTrashed()->withDefault();
    }

    /**
     * Get the Warehouses associated with the user.
     */
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'user_warehouse', 'user_id', 'warehouse_id')
            ->withTimestamps();
    }

    /**
     * Get the user incharges of the warehouses.
     */
    public function incharges()
    {
        return $this->hasMany(Warehouse::class, 'incharge');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function openTickets()
    {
        return $this->tickets()->where('status', '<', Ticket::STATUS_SOLVED);
    }

    public function solvedTickets()
    {
        return $this->tickets()->where('status', '=', Ticket::STATUS_SOLVED);
    }

    public function closedTickets()
    {
        return $this->tickets()->where('status', '=', Ticket::STATUS_CLOSED);
    }

    /**
     * User has many blog post
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id');
    }

    /**
     * Get role list for the user.
     *
     * @return array
     */
    public function getRoleListAttribute()
    {
        if (count($this->roles)) {
            return $this->roles->pluck('id')->toArray();
        }
    }

    /**
     * Set password for the user.
     *
     * @return array
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    /**
     * Get name the user.
     *
     * @return mix
     */
    public function getName()
    {
        return $this->nice_name ?: $this->name;
    }
}
