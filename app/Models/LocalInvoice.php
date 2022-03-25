<?php

namespace App\Models;

class LocalInvoice extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the shop that owns the invoice.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
