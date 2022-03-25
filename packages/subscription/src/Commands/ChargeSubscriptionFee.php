<?php

namespace Incevio\Package\Subscription\Commands;

use App\Shop;
use Exception;
use Carbon\Carbon;
use App\Subscription;
use Illuminate\Console\Command;
use App\Services\DbService;

// use Incevio\Package\Subscription\Models\Wallet;
// use Incevio\Package\Subscription\Services\DbService;

/**
 * Class RefreshBalance.
 */
class ChargeSubscriptionFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all subscriptions and charge expired one';

    /**
     * @return void
     * @throws
     */
    public function handle(): void
    {
        Subscription::query()->each(static function (Subscription $subscription) {

            if ($subscription->provider == 'wallet' && $subscription->active()) {

                $expire_time = Carbon::now()->addMinutes(config('subscription.default.charge_min_before_expiry'));

                //Skip if the charging time is not arrived
                if (
                    ($subscription->onTrial() && $subscription->trial_ends_at->lte($expire_time)) ||
                    ($subscription->onGracePeriod() && $subscription->ends_at->lte($expire_time))
                ) {
                    try {
                        \Log::info('Billing: ' . $subscription->owner->name);

                        if (! $cost = $subscription->owner->custom_subscription_fee) {
                            $cost = \DB::table('subscription_plans')->select('cost')->where('plan_id', $subscription->stripe_id)->first()->cost;
                        }

                        // Check balance
                        if ($subscription->owner->balance < $cost) {
                            \Log::info('Subscription charges (shop:: '.$subscription->owner->name.') failed beacuse of insufficient balance.');
                        }
                        else {
                            $meta = [
                                'type' => trans('app.subscription_fee'),
                                'subscription_id' => $subscription->stripe_id,
                                'description' => trans('subscription::lang.subscription_fee', ['subscription' => $subscription->name])
                            ];

                            $subscription->owner->withdraw($cost ? $cost : 0, $meta);

                            // Payment success, update end time
                            $subscription->ends_at = $subscription->ends_at ?
                                                    $subscription->ends_at->addMonth() :
                                                    $subscription->trial_ends_at->addMonth();
                            $subscription->save();
                        }
                    }
                    catch (Exception $e) {

                        // Set end time if on trial
                        if ($subscription->onTrial()) {
                            $subscription->ends_at = $subscription->trial_ends_at;
                            $subscription->save();
                        }

                        \Log::info('Subscription charges (shop:: '.$subscription->owner->name.') failed beacuse of ' . $e->getMessage());
                    }
                }
            }
        });
    }
}
