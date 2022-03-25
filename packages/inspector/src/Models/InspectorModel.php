<?php

namespace Incevio\Package\Inspector\Models;

use App\BaseModel;

class InspectorModel extends BaseModel
{

    const INSPECTION_STATUS_APPROVED   = 1; // Default
    const INSPECTION_STATUS_PENDING    = 2;
    const INSPECTION_STATUS_BLOCK      = 3;

    protected $table = 'inspections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['caught', 'status','attempts'];

    /**
     * The inspectable model
     *
     * @var array
     */
    public function inspectable()
    {
        return $this->morphTo('inspectable');
    }
}
