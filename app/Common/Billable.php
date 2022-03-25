<?php

namespace App\Common;

use App\Models\LocalInvoice;
use App\Models\Subscription;
use App\Models\SystemConfig;
use App\Models\SubscriptionPlan;
use Laravel\Cashier\Billable as CashierBillable;
// use Mpociot\VatCalculator\VatCalculator;
use Laravel\Cashier\SubscriptionBuilder;
use Stripe\Customer as StripeCustomer;

// use Incevio\Package\Subscription\SubscriptionBuilder as Incevio\Package\Subscription\SubscriptionBuilder\LocalSubscriptionBuilder;

trait Billable
{
    use CashierBillable;

    /**
     * Determine if the user is connected ot any payment provider.
     *
     * @return bool
     */
    // public function hasBillingProvider()
    // {
    //     return $this->stripe_id;
    // }

    /**
     * Get all of the subscription for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all of the subscriptions for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all of the local invoices.
     */
    public function localInvoices()
    {
        return $this->hasMany(LocalInvoice::class)->orderBy('id', 'desc');
    }

    /**
     * Begin creating a new subscription.
     *
     * @param  App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Laravel\Cashier\SubscriptionBuilder
     */
    public function newSubscription(SubscriptionPlan $subscriptionPlan)
    {
        if (SystemConfig::isBillingThroughWallet()) {
            $subscription = new \Incevio\Package\Subscription\SubscriptionBuilder($this, $subscriptionPlan->name, $subscriptionPlan->plan_id);

            $subscription->setSubscriptionFee($subscriptionPlan->cost);

            return $subscription;
        }

        return new SubscriptionBuilder($this, $subscriptionPlan->name, $subscriptionPlan->plan_id);
    }

    /**
     * Check if the billable model has an active subscription.
     *
     * @return bool
     */
    public function hasActiveSubscription()
    {
        return $this->currentSubscription &&
            ($this->currentSubscription->ends_at === null ||
                $this->currentSubscription->ends_at->isFuture() ||
                $this->currentSubscription->trial_ends_at !== null &&
                $this->currentSubscription->trial_ends_at->isFuture()
            );
    }

    /**
     * Create a Stripe customer for the given model.
     *
     * @param  array  $options
     * @return \Stripe\Customer
     */
    // public function createAsStripeCustomer(array $options = [])
    // {
    //     $options = array_key_exists('email', $options)
    //             ? $options
    //             : array_merge($options, ['email' => $this->email]);

    //     $options = array_merge($options, [
    //         'expand' => [
    //             'subscriptions'
    //         ]
    //     ]);

    //     // Here we will create the customer instance on Stripe and store the ID of the
    //     // user from Stripe. This ID will correspond with the Stripe user instances
    //     // and allow us to retrieve users from Stripe later when we need to work.
    //     $customer = StripeCustomer::create(
    //         $options, $this->stripeOptions()
    //     );

    //     $this->stripe_id = $customer->id;

    //     $this->save();

    //     return $customer;
    // }

    /**
     * Get the Stripe customer for the model.
     *
     * @return \Stripe\Customer
     */
    // public function asStripeCustomer()
    // {
    //     $options = [
    //         'id' => $this->stripe_id,
    //         'expand' => [
    //             'subscriptions'
    //         ]
    //     ];

    //     return StripeCustomer::retrieve($options, $this->stripeOptions());
    // }

    // public static function retrieve($id, $opts = null)
    // {
    //     dd($opts);
    //     $opts = \Stripe\Util\RequestOptions::parse($opts);
    //     $instance = new static($id, $opts);
    //     $instance->refresh();
    //     return $instance;
    // }

    /**
     * Get the tax percentage to apply to the subscription.
     *
     * @return int
     */
    // public function taxPercentage()
    // {
    //     if (! Spark::collectsEuropeanVat()) {
    //         return 0;
    //     }

    //     $vatCalculator = new VatCalculator;

    //     $vatCalculator->setBusinessCountryCode(Spark::homeCountry());

    //     return $vatCalculator->getTaxRateForCountry(
    //         $this->card_country, ! empty($this->vat_id)
    //     ) * 100;
    // }
}
