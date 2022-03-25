<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends BaseModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rtl' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * Scope a query to only include active records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = (bool) $value;
    }

    public function setRtlAttribute($value)
    {
        $this->attributes['rtl'] = (bool) $value;
    }

    /**
     * Setters
     */
    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = $value ?: 100;
    }
}
