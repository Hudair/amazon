<?php

namespace App\Models;

class ContactUs extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contact_us';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = ['read' => 'boolean'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'message',
        'read',
    ];
}
