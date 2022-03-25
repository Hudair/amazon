<?php

namespace App\Models;

class CancellationReason extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cancellation_reasons';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'office_use' => 'boolean',
    ];

    /**
     * Scope a query to only include approved cancellations.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFront($query)
    {
        return $query->whereNull('office_use');
    }

    /**
     * Scope a query to only include approved cancellations.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOffice($query)
    {
        return $query->whereNotNull('office_use');
    }
}
