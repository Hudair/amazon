<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Helpers\Statistics;
use App\Jobs\SubscribeShopToNewPlan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\UpdateTrialPeriodRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription features.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function features(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription_plan._show', compact('subscriptionPlan'));
    }

    /**
     * Subscribe Or Swap to the given subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $plan
     * @param  int $merchant
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request, $plan, $merchant = null)
    {
        if (config('app.demo') == true && $request->user()->merchantId() <= config('system.demo.shops', 1)) {
            return redirect()->route('admin.account.billing')
                ->with('warning', trans('messages.demo_restriction'));
        }

        $merchant = $merchant ? User::findOrFail($merchant) : Auth::user();

        if (is_billing_info_required() && !$merchant->hasBillingToken()) {
            return redirect()->route('admin.account.billing')
                ->with('error', trans('messages.no_card_added'));
        }

        // create the subscription
        try {
            $subscription = SubscriptionPlan::findOrFail($plan);

            // If the merchant already has any subscription then just swap to new plan
            $currentPlan = $merchant->getCurrentPlan();

            if ($currentPlan) {
                if (!$this->validateSubscriptionSwap($subscription)) {
                    $msg = trans('messages.using_more_resource', ['plan' => $subscription->name]);

                    return redirect()->route('admin.account.billing')->with('error', $msg);
                }

                $currentPlan->swap($plan)->update(['name' => $subscription->name]);

                $merchant->shop->forceFill([
                    'current_billing_plan' => $plan,
                ])->save();
            } else {
                // Subscribe the merchant to the given plan
                SubscribeShopToNewPlan::dispatchSync($merchant, $plan);
            }
        } catch (\Exception $e) {
            Log::error('Subscription Failed: ' . $e->getMessage());

            return redirect()->route('admin.account.billing')
                ->with('error', trans('messages.subscription_error'));
        }

        return redirect()->route('admin.account.billing')
            ->with('success', trans('messages.subscribed'));
    }

    /**
     * Update the shop's card info
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCardinfo(Request $request)
    {
        if (config('app.demo') == true && $request->user()->merchantId() <= config('system.demo.shops', 1)) {
            return redirect()->route('admin.account.billing')
                ->with('warning', trans('messages.demo_restriction'));
        }

        if (!$request->user()->hasBillingToken()) {
            $request->user()->shop->createAsStripeCustomer([
                'email' => $request->user()->email,
            ]);
        }

        if ($request->has('payment')) {
            $request->user()->shop->updateDefaultPaymentMethod($request->input('payment'));

            return redirect()->route('admin.account.billing')
                ->with('success', trans('messages.card_updated'));
        }

        return redirect()->route('admin.account.billing')
            ->with('error', trans('messages.trouble_validating_card'))->withInput();
    }

    /**
     * Resume subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resumeSubscription(Request $request)
    {
        if (
            config('app.demo') == true &&
            $request->user()->merchantId() <= config('system.demo.shops', 1)
        ) {
            return redirect()->route('admin.account.billing')
                ->with('warning', trans('messages.demo_restriction'));
        }

        try {
            $request->user()->getCurrentPlan()->resume();
        } catch (\Stripe\Error\Card $e) {
            $response = $e->getJsonBody();

            return redirect()->route('admin.account.billing')
                ->with('error', $response['error']['message']);
        }

        return redirect()->route('admin.account.billing')
            ->with('success', trans('messages.subscription_resumed'));
    }

    /**
     * Cancel subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscription(Request $request)
    {
        if (config('app.demo') == true && $request->user()->merchantId() <= config('system.demo.shops', 1)) {
            return redirect()->route('admin.account.billing')
                ->with('warning', trans('messages.demo_restriction'));
        }

        try {
            $plan = $request->user()->getCurrentPlan();

            if ($plan) {
                $plan->cancel();
            } else {
                throw new \Exception(trans('responses.subscription_404'));
            }
        } catch (\Stripe\Error\Card $e) {
            $response = $e->getJsonBody();

            return redirect()->route('admin.account.billing')
                ->with(['error' => $response['error']['message']]);
        } catch (\Exception $e) {
            return redirect()->route('admin.account.billing')
                ->with(['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.account.billing')
            ->with('success', trans('messages.subscription_cancelled'));
    }

    /**
     * Update subscription trial priod
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Shop    $shop
     *
     * @return \Illuminate\Http\Response
     */
    public function editTrial(Request $request, Shop $shop)
    {
        return view('admin.shop._edit_trial', compact('shop'));
    }

    /**
     * Update subscription trial priod
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Shop    $shop
     *
     * @return \Illuminate\Http\Response
     */
    public function updateTrial(UpdateTrialPeriodRequest $request, Shop $shop)
    {
        $new_end_time = Carbon::createFromFormat('Y-m-d h:i a', $request->trial_ends_at);

        try {
            $currentPlan = $shop->owner->getCurrentPlan();

            if ($currentPlan) {
                // Update the local plan
                $currentPlan->update(['trial_ends_at' => $new_end_time->getTimestamp()]);

                // Now update the plan on stripe
                if ($currentPlan->stripe_id || config('system.subscription.billing') == 'stripe') {
                    $currentPlan->extendTrial($new_end_time);
                }
            }

            if ($shop->onGenericTrial() || $shop->hasExpiredPlan()) {
                $shop->forceFill([
                    'trial_ends_at' => $new_end_time->getTimestamp(),
                    'hide_trial_notice' => $request->hide_trial_notice,
                ])->save();
            }
        } catch (\Exception $e) {
            Log::error('Subscription Trial Period Update Failed: ' . $e->getMessage());

            return back()->with('error', trans('messages.subscription_update_failed'));
        }

        return back()->with('success', trans('messages.subscription_updated'));
    }

    /**
     * Validate new plan with the current plan
     *
     * @param  App\Models\SubscriptionPlan $plan
     * @return bool
     */
    private function validateSubscriptionSwap(SubscriptionPlan $plan)
    {
        $resources = [
            'users' => Statistics::shop_user_count(),
            'inventories' => Statistics::shop_inventories_count(),
        ];

        return $resources['users'] <= $plan->team_size && $resources['inventories'] <= $plan->inventory_limit;
    }

    public function invoice(Request $request, $invoiceId)
    {
        return $request->user()->shop
            ->downloadInvoice($invoiceId, [
                'vendor' => get_platform_title(),
                'product' => trans('app.subscription_fee'),
            ]);
    }
}
