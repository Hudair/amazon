<?php

namespace App\Events\Subscription;

use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Route;

class Saving
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SubscriptionPlan $subscriptionPlan)
    {
        // if (Route::currentRouteName() == 'admin.setting.subscriptionPlan.update' && $subscriptionPlan->featured)
        if ($subscriptionPlan->featured) {
            SubscriptionPlan::where('featured', 1)->update(['featured' => 0]);
        }
    }
}
