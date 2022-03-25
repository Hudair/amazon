<?php

namespace App\Models;

class Currency extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'currencies';

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'symbol_first' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get all of the countries for the currency.
     */
    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    /**
     * Scope a query to only include active records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Setters
     */
    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = (bool) $value;
    }

    public function setSymbolFirstAttribute($value)
    {
        $this->attributes['symbol_first'] = (bool) $value;
    }

    // public function setAlternateSymbolsAttribute($value)
    // {
    //     $this->attributes['alternate_symbols'] = serialize($value);
    // }

    /**
     * Getters
     */
    // public function getAlternateSymbolsAttribute($value)
    // {
    //     return unserialize($value);
    // }
}
