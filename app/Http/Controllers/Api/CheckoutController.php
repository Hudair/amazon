<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Common\ShoppingCart;
use App\Events\Order\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CheckoutCartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaymentMethodResource;
use App\Services\Payments\PaymentService;
use App\Contracts\PaymentServiceContract as PaymentGateway;
// use App\Contracts\PaymentServiceContract as PaymentService;
// use App\Http\Requests\Validations\DirectCheckoutRequest;
use App\Exceptions\PaymentFailedException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    use ShoppingCart;

    /**
     * Checkout the specified cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(CheckoutCartRequest $request, Cart $cart, PaymentGateway $payment)
    {
        $cart = crosscheckAndUpdateOldCartInfo($request, $cart);

        DB::beginTransaction();

        try {
            // Create the order
            $order = $this->saveOrderFromCart($request, $cart);

            $receiver = vendor_get_paid_directly() ? 'merchant' : 'platform';

            // When the order has been paid on the app end
            if ($request->input('payment_status') == 'paid' && $request->has('payment_meta')) {
                $response = $payment->verifyPaidPayment();
            } else {
                $response = $payment->setReceiver($receiver)
                    ->setOrderInfo($order)
                    ->setAmount($order->grand_total)
                    ->setDescription(trans('app.purchase_from', [
                        'marketplace' => get_platform_title()
                    ]))
                    ->setConfig()
                    ->charge();
            }

            switch ($response->status) {
                case PaymentService::STATUS_PAID:
                    $order->markAsPaid();     // Order has been paid
                    break;

                case PaymentService::STATUS_PENDING:
                    if ($order->paymentMethod->code == 'cod') {
                        $order->order_status_id = Order::STATUS_CONFIRMED;
                        $order->payment_status = Order::PAYMENT_STATUS_UNPAID;
                    } else {
                        $order->order_status_id = Order::STATUS_WAITING_FOR_PAYMENT;
                        $order->payment_status = Order::PAYMENT_STATUS_PENDING;
                    }
                    break;

                case PaymentService::STATUS_ERROR:
                    $order->payment_status = Order::PAYMENT_STATUS_PENDING;
                    $order->order_status_id = Order::STATUS_PAYMENT_ERROR;
                default:
                    throw new PaymentFailedException(trans('theme.notify.payment_failed'));
            }

            // throw new \Exception("Error Payment Processing Request");
        } catch (\Exception $e) {
            DB::rollback(); // rollback the transaction and log the error

            Log::error($request->payment_method . ' Payment failed:: ' . $e->getMessage());
            Log::error($e);

            return response()->json([
                'error' => $e->getMessage(),
                'cart' => new CartResource($cart),
            ], 403);
        }

        // Order confirmed
        if ($order->paymentMethod->type !== PaymentMethod::TYPE_MANUAL) {
            // Order has been paid
            $order->markAsPaid();
        }

        // Everything is fine. Now commit the transaction
        DB::commit();

        // Delete the cart
        // $cart->forceDelete();

        // Trigger the Event
        event(new OrderCreated($order));

        return response()->json([
            'message' => trans('theme.notify.order_placed'),
            'order' => new OrderResource($order),
        ], 200);
    }

    /**
     * Return available payment options options for the cart.
     *
     * @param  Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function paymentOptions(Cart $cart)
    {
        // Get the shop
        $shop = $cart->shop;

        // Get all active payment methods
        $activePaymentMethods = PaymentMethod::active()->get();
        $activePaymentCodes = $activePaymentMethods->pluck('code')->toArray();

        $shop_config = Null;
        if (vendor_get_paid_directly()) {
            $activePaymentMethods = $shop->paymentMethods;
            $shop_config = $shop;
        }

        $results = collect([]);
        foreach ($activePaymentMethods as $payment) {
            if (
                !in_array($payment->code, $activePaymentCodes) ||
                !get_payment_config_info($payment->code, $shop_config)
            ) {
                continue;
            }

            $results->push($payment);
        }

        return PaymentMethodResource::collection($results);
    }

    public function stripePaymentIntent(Request $request, Cart $cart)
    {
        // $request->merge(['payment_method_id' => 2]);

        // // Create the order
        // $order = $this->saveOrderFromCart($request, $cart);

        // $receiver = vendor_get_paid_directly() ? 'merchant' : 'platform';

        // $paymentService = new \App\Services\Payments\StripePaymentService($request);

        // $response = $paymentService->setReceiver($receiver)
        //     ->setOrderInfo($order)
        //     ->setAmount($order->grand_total)
        //     ->setDescription(trans('app.purchase_from', ['marketplace' => get_platform_title()]))
        //     ->setConfig();
        // ->charge();

        // $st = \Stripe\Token::create([
        //     'amount' => 1999,
        //     'currency' => 'usd'
        // ]);

        // $st = new \Stripe\StripeClient(config('services.stripe.secret'));

        // Log::info($response);

        // $cart = $this->moveAllItemsToCartAgain($order, true, $cart->id);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $newToken = \Stripe\Token::create([
            'card' => [
                'number' => $request->card_number,
                'exp_month' => $request->exp_month,
                'exp_year' => $request->exp_year,
                'cvc' => $request->cvc,
            ],
        ], ['stripe_account' => config('services.stripe.account_id')]);

        return json_encode($newToken);
    }
}
