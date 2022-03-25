<?php

namespace App\Policies;

use App\Helpers\Authorize;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPlanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view subscriptionPlans.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_subscription_plan'))->check();
    }

    /**
     * Determine whether the user can view the SubscriptionPlan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return mixed
     */
    public function view(User $user, SubscriptionPlan $subscriptionPlan)
    {
        return (new Authorize($user, 'view_subscription_plan', $subscriptionPlan))->check();
    }

    /**
     * Determine whether the user can create SubscriptionPlans.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_subscription_plan'))->check();
    }

    /**
     * Determine whether the user can update the SubscriptionPlan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return mixed
     */
    public function update(User $user, SubscriptionPlan $subscriptionPlan)
    {
        return (new Authorize($user, 'edit_subscription_plan', $subscriptionPlan))->check();
    }

    /**
     * Determine whether the user can delete the SubscriptionPlan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return mixed
     */
    public function delete(User $user, SubscriptionPlan $subscriptionPlan)
    {
        return (new Authorize($user, 'delete_subscription_plan', $subscriptionPlan))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_subscription_plan'))->check();
    }
}
