<?php

namespace App\Models;

class State extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'states';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the country of the state.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Check if the state is in active business area
     *
     * @return bool
     */
    public function getInActiveBusinessAreaAttribute()
    {
        return config('system_settings.worldwide_business_area') ? true : $this->active;
    }
}
