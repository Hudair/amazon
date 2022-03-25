<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvgFeedback extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'avg_feedback';

    /**
     * Get all of the owning feedbackable models.
     */
    public function feedbackable()
    {
        return $this->morphTo();
    }
}
