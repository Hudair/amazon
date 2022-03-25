<?php

namespace Incevio\Package\oneCheckout\Http\Controllers\Api;

use App\Models\Order;
use App\Common\ShoppingCart;
use App\Events\Order\OrderCreated;
use App\Services\Payments\PaymentService;
use App\Contracts\PaymentServiceContract as PaymentGateway;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Validations\CheckoutCartRequest;
use App\Exceptions\PaymentFailedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OneCheckoutController
{
    use ShoppingCart;

    /**
     * Place Order
     */
    public function checkout(CheckoutCartRequest $request, PaymentGateway $payment)
    {
        $carts = $this->getShoppingCarts();

        // If cart if empty
        if ($carts->count() == 0) {
            return response()->json([
                'warning' => trans('theme.notify.cart_empty')
            ], 404);
        }

        if (vendor_get_paid_directly()) {
            Log::error("oneChecout Error: oneCheckout work only when the Wallet module is enabled and admin receive payment.");

            return response()->json([
                'error' => trans('checkout::lang.onecheckout_not_available')
            ], 400);
        }

        // Check all carts has same shipping area
        if (
            count(array_unique($carts->pluck('ship_to_country_id')->toArray())) != 1 ||
            count(array_unique($carts->pluck('ship_to_state_id')->toArray())) != 1
        ) {
            return response()->json([
                'warning' => trans('checkout::lang.checkout_all_not_possible')
            ], 400);
        }

        // Create the order
        $orders = [];
        $payable_amount = 0;

        // Start transaction!
        DB::beginTransaction();
        try {
            foreach ($carts as $key => $cart) {
                $orders[$key] = $this->saveOrderFromCart($request, $cart);
                $payable_amount += $orders[$key]->calculate_grand_total();
                $order = $orders[$key];
            }

            if ($request->input('payment_status') == 'paid' && $request->has('payment_meta')) {
                $response = $payment->verifyPaidPayment();
            } else {
                $response = $payment->setReceiver('platform')
                    ->setOrderInfo($orders)
                    ->setAmount($payable_amount)
                    ->setDescription(trans('app.purchase_from', [
                        'marketplace' => get_platform_title()
                    ]))
                    ->setConfig()
                    ->charge();
            }

            foreach ($orders as $order) {
                if ($response->status == PaymentService::STATUS_PAID) {
                    // Order has been paid
                    $order->markAsPaid();
                } else if ($response->status == PaymentService::STATUS_PENDING) {
                    if ($order->paymentMethod->code == 'cod') {
                        $order->order_status_id = Order::STATUS_CONFIRMED;
                        $order->payment_status = Order::PAYMENT_STATUS_UNPAID;
                    } else {
                        $order->order_status_id = Order::STATUS_WAITING_FOR_PAYMENT;
                        $order->payment_status = Order::PAYMENT_STATUS_PENDING;
                    }
                } else {
                    throw new PaymentFailedException(trans('theme.notify.payment_failed'));
                }

                // Save the order
                $order->save();
            }
        } catch (\Exception $e) {
            // rollback the transaction and log the error
            DB::rollback();

            Log::error('Payment failed oneCheckout:: ' . $e->getMessage());
            Log::error($e);

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        // Everything is fine. Now commit the transaction
        DB::commit();

        // Trigger the Event
        event(new OrderCreated($order));

        return response()->json([
            'message' => trans('theme.notify.order_placed'),
            'order' => new OrderResource($order),
        ], 200);
    }
}
