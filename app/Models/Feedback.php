<?php

namespace App\Models;

use App\Common\Attachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends BaseModel
{
    use HasFactory, Attachable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'approved' => 'boolean',
        'spam' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'rating',
        'comment',
        'feedbackable_id',
        'feedbackable_type',
        'approved',
        'spam',
    ];

    /**
     * Get all of the owning feedbackable models.
     */
    public function feedbackable()
    {
        return $this->morphTo();
    }

    /**
     * Get the customer associated with the model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    /**
     * Set the rating for the model.
     */
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = $value ? (int) $value : 1;
    }

    /**
     * Returns translated label text
     *
     * @return array labels
     */
    public function getLabels()
    {
        $labels = [];

        $labels[] = trans('theme.verified_purchase');

        return $labels;
    }
}
