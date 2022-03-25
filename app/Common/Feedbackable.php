<?php

namespace App\Common;

use App\Models\AvgFeedback;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

/**
 * Attach this Trait to a User (or other model) for easier read/writes on Feedbacks
 *
 * @author Munna Khan
 */
trait Feedbackable
{
    /**
     * Check if model has any Feedbacks.
     *
     * @return bool
     */
    public function hasFeedbacks()
    {
        return (bool) $this->lastFeedback()->count();
    }

    /**
     * Return last feedback related to the feedbackable model
     */
    public function lastFeedback()
    {
        return $this->morphOne(Feedback::class, 'feedbackable')
            ->latest()->orderBy('created_at', 'desc');
    }

    /**
     * Return last feedback related to the feedbackable model
     */
    public function latestFeedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedbackable')
            ->latest()->orderBy('created_at', 'desc')->limit(10);
    }

    /**
     * Return collection of feedbacks related to the feedbackable model
     */
    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedbackable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Return average ratings related to the feedbackable model
     */
    // public function ratings()
    public function avgFeedback()
    {
        return $this->morphOne(AvgFeedback::class, 'feedbackable');
    }

    public function getRatingsAttribute()
    {
        return optional($this->avgFeedback)->rating;
    }

    public function getRatingsCountAttribute()
    {
        return optional($this->avgFeedback)->count;
    }

    // public function averageFeedback()
    // {
    //     return $this->morphMany(Feedback::class, 'feedbackable')
    //     ->select('rating','feedbackable_id','feedbackable_type')
    //     ->select('*', \DB::raw('AVG(rating) AS ratings'));
    //     // ->avg('rating');
    // }

    public function rating()
    {
        $rating = $this->ratings;
        $dec = ((int) $rating == $rating) ? 0 : 1;

        return ceil($rating) ? number_format($rating, $dec) : null;
    }

    public function sumFeedback()
    {
        return $this->feedbacks()->sum('rating');
    }

    public function userAverageFeedback()
    {
        return $this->feedbacks()
            ->where('customer_id', Auth::guard('customer')->id())
            ->avg('rating');
    }

    public function customerSumFeedback()
    {
        return $this->feedbacks()
            ->where('customer_id', Auth::guard('customer')->id())
            ->sum('rating');
    }

    public function ratingPercent($max = 5)
    {
        $quantity = $this->feedbacks()->count();
        $total = $this->sumFeedback();

        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    public function getAverageFeedbackAttribute()
    {
        return $this->averageFeedback();
    }

    public function getSumFeedbackAttribute()
    {
        return $this->sumFeedback();
    }

    public function getCustomerAverageFeedbackAttribute()
    {
        return $this->userAverageFeedback();
    }

    public function getCustomerSumFeedbackAttribute()
    {
        return $this->customerSumFeedback();
    }

    /**
     * Deletes all the Feedbacks of this model.
     *
     * @return bool
     */
    public function flushFeedbacks()
    {
        $feedbacks = $this->feedbacks();

        foreach ($feedbacks->get() as $feedback) {
            if ($feedback->hasAttachments()) {
                $feedback->flushAttachments();
            }
        }

        return $feedbacks->delete();
    }
}
