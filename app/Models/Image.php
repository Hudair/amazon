<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class Image extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'path',
        'extension',
        'size',
        'order',
        'featured', // will be removed
        'imageable_id',
        'imageable_type',
    ];

    /**
     * Get all of the owning imageable models.
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    public function setFeatureAttribute($value)
    {
        $this->attributes['featured'] = (bool) $value ? $value : null;
    }

    // public function getUrlAttribute()
    // {
    //     return Storage::url($this->path);
    // }

    public function getUploadedTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getSizeInKbAttribute()
    {
        return $this->size ? round($this->size / 1024, 2) : null;
    }
}
