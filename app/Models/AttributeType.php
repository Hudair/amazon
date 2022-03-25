<?php

namespace App\Models;

class AttributeType extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_types';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * AttributeType has many Attributes
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
}
