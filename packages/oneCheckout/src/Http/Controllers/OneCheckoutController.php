<?php

namespace Incevio\Package\oneCheckout\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Common\ShoppingCart;
use App\Events\Order\OrderCreated;
use App\Services\Payments\PaymentService;
use App\Contracts\PaymentServiceContract as PaymentGateway;
use App\Http\Requests\Validations\CheckoutCartRequest;
use App\Exceptions\PaymentFailedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OneCheckoutController
{
    use ShoppingCart;

    /**
     * Show checkout form
     */
    public function index(Request $request)
    {
        $carts = $this->getAllCartsToProceed($request);

        if ($carts instanceof RedirectResponse) {
            return $carts;
        }

        $paymentMethods = PaymentMethod::active()->get();

        $customer = Auth::guard('customer')->check() ? Auth::guard('customer')->user() : Null;

        return view('checkout::checkout', compact('carts', 'customer', 'paymentMethods'));
    }

    /**
     * Place Order
     */
    public function checkout(CheckoutCartRequest $request, PaymentGateway $payment)
    {
        $carts = $this->getAllCartsToProceed($request);

        if ($carts instanceof RedirectResponse) {
            return $carts;
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

            $response = $payment->setReceiver('platform')
                ->setOrderInfo($orders)
                ->setAmount($payable_amount)
                ->setDescription(trans('app.purchase_from', [
                    'marketplace' => get_platform_title()
                ]))
                ->setConfig()
                ->charge();

            // Check if the result is a RedirectResponse of Paypal and some other gateways
            if ($response instanceof RedirectResponse) {
                // Everything is fine. Now commit the transaction
                DB::commit();

                return $response;
            }

            foreach ($orders as $order) {
                switch ($response->status) {
                    case PaymentService::STATUS_PAID:
                        $order->markAsPaid();      // Order has been paid
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

                // Save the order
                $order->save();
            }
        } catch (\Exception $e) {
            DB::rollback(); // rollback the transaction and log the error

            Log::error('Payment failed oneCheckout:: ' . $e->getMessage());
            Log::error($e);

            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        // Everything is fine. Now commit the transaction
        DB::commit();

        // Trigger the Event
        event(new OrderCreated($order));

        return view('theme::order_complete', compact('order'))
            ->with('success', trans('theme.notify.order_placed'));
    }

    /**
     * Return all carts for the current customer
     */
    private function getAllCartsToProceed($request)
    {
        if (vendor_get_paid_directly()) {
            Log::error("oneChecout Error: oneCheckout work only when the Wallet module is enabled and admin receive payment.");

            return redirect()->route('cart.index')
                ->with('warning', trans('checkout::lang.onecheckout_not_available'));
        }

        $carts = $this->getShoppingCarts();

        // In single cart redirect to normal checkout
        if ($carts->count() == 1) {
            return redirect()->route('cart.checkout', $carts->first());
        }
        // If cart if empty
        else if ($carts->count() == 0) {
            return redirect()->route('cart.index')
                ->with('warning', trans('theme.notify.cart_empty'));
        }

        // Check all carts has same shipping area
        if (
            count(array_unique($carts->pluck('ship_to_country_id')->toArray())) != 1 ||
            count(array_unique($carts->pluck('ship_to_state_id')->toArray())) != 1
        ) {
            return redirect()->route('cart.index')
                ->with('warning', trans('checkout::lang.checkout_all_not_possible'));
        }

        return $carts;
    }
}
