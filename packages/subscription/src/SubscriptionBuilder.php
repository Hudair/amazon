<?php

namespace Incevio\Package\Subscription;

use Carbon\Carbon;
// use DateTimeInterface;
use Laravel\Cashier\SubscriptionBuilder as CashierSubscriptionBuilder;

class SubscriptionBuilder extends CashierSubscriptionBuilder
{
    /**
     * The subscription fee
     *
     * @var int
     */
    protected $subscriptionFee = 0;

    public function setSubscriptionFee($price)
    {
        $this->subscriptionFee = $price;
    }

    /**
     * Create a new Local subscription.
     *
     * @param  \Stripe\PaymentMethod|string|null  $paymentMethod
     * @param  array  $options
     * @return \Laravel\Cashier\Subscription
     */
    public function create($paymentMethod = null, array $options = [])
    {
        if ($this->skipTrial) {
            $trialEndsAt = null;
        } else {
            $trialEndsAt = $this->trialExpires;
        }

        $subscription = $this->owner->subscriptions()->create([
            'name' => $this->name,
            'stripe_id' => $this->plan,
            'quantity' => $this->quantity,
            'trial_ends_at' => $trialEndsAt,
            'ends_at' => $trialEndsAt ? Null : Carbon::now()->addMonth(),
        ]);

        if ($this->skipTrial || ! $trialEndsAt || ! $subscription->trial_ends_at->isFuture() && $this->subscriptionFee > 0) {
            $meta = [
                'type' => trans('app.subscription_fee'),
                'description' => trans('subscription::lang.subscription_fee', ['subscription' => $this->name]),
            ];

            $this->owner->forceWithdraw($this->subscriptionFee, $meta);
        }

        return $subscription;
    }
}
