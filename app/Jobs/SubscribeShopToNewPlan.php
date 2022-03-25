<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Bus\Dispatchable;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Carbon\Carbon;

class SubscribeShopToNewPlan
{
    use Dispatchable;

    protected $merchant;
    protected $plan;
    protected $payment_method;

    /**
     * Create a new job instance.
     *
     * @param  User  $merchant
     * @param  string  $plan
     * @param  str/Null  $payment_method
     * @return void
     */
    public function __construct(User $merchant, $plan, $payment_method = null)
    {
        $this->merchant = $merchant;
        $this->plan = $plan;
        $this->payment_method = $payment_method;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shop = $this->merchant->owns;

        // Create subscription intance
        $subscriptionPlan = SubscriptionPlan::findOrFail($this->plan);

        $subscription = $shop->newSubscription($subscriptionPlan);

        // Subtract the used trial days with the new subscription
        if ($shop->onGenericTrial()) {
            $trialDays = Carbon::now()->lt($shop->trial_ends_at) ? Carbon::now()->diffInDays($shop->trial_ends_at) : null;
        } else {
            $trialDays = (bool) config('system_settings.trial_days') ? config('system_settings.trial_days') : null;
        }

        // Set trial days
        if ($trialDays) {
            $subscription->trialDays($trialDays);
        } else {
            $subscription->skipTrial();
        }

        // Create subscription
        try {
            $subscription = $subscription->create($this->payment_method, [
                'email' => $this->merchant->email,
            ]);

            // Update shop model
            $shop->forceFill([
                'current_billing_plan' => $this->plan,
                'trial_ends_at' => $subscription->trial_ends_at,
            ])->save();
        } catch (IncompletePayment $e) {
            return redirect()->route('cashier.payment', [$e->payment->id, 'redirect' => route('home')]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
