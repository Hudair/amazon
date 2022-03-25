<?php

namespace Incevio\Package\Wallet\Http\Controllers;

use DB;
use Auth;
use App\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Contracts\PaymentServiceContract;
use Incevio\Package\Wallet\Http\Requests\DepositRequest;
use App\Services\Payments\PaypalExpressPaymentService;
use Incevio\Package\Wallet\Jobs\PayoutJob;
use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Notifications\Deposit;
use App\Services\Payments\PaystackPaymentService;

class DepositController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_form(Request $request)
    {

        $paymentMethods = PaymentMethod::find(get_from_option_table('wallet_payment_methods', []));

        if (Auth::guard('customer')->check()) {
            $tab = 'wallet';
            // View loaded from theme directory, need to do in better ways
            $content = view('wallet::customer.deposit', compact('paymentMethods'))->render();

            return view('theme::dashboard', compact('tab','content'));

        }

        if (Auth::user()->isMerchant()){
            $merchant = Auth::user()->shop;
            return view('wallet::deposit', compact('paymentMethods','merchant'));
        }

    }

    /**
     * @param DepositRequest $request
     * @param PaymentServiceContract $paymentService
     * @return RedirectResponse
     */
    public function deposit(DepositRequest $request, PaymentServiceContract $paymentService)
    {
        try {
            $result = $paymentService->setReceiver('platform')
                ->setAmount($request->amount)
                ->setDescription(trans('wallet::lang.deposit_description', ['marketplace' => get_platform_title()]))
                ->setConfig()
                ->charge();

            // Check if the result is a RedirectResponse of Paypal and some other gateways
            if($result instanceof RedirectResponse) {
                return $result;
            }
        }
        catch (\Exception $e) {
            \Log::error('Payment failed:: ');
            \Log::info($e);

            return redirect()->route(config('wallet.routes.deposit_form'))
            ->with('error', $e->getMessage())->withInput();
            // ->with('error', trans('wallet::lang.payment_failed'))->withInput();
        }

        // Payment succeed
        if ($result->success) {

            $meta['description'] = trans('wallet::lang.deposit_description',
                ['marketplace' => get_platform_title(), 'payment' => $request->payment_method]);

            return $this->depositCompleted($request->amount, $meta);
        }

        return redirect()->route(config('wallet.routes.deposit_form'))
        ->with('error', trans('wallet::lang.payment_failed'))->withInput();

    }

    /**
     * @param Request $request
     * @param PaypalExpressPaymentService $paymentService
     * @return RedirectResponse
     */
    public function paypalPaymentSuccess(Request $request, PaypalExpressPaymentService $paymentService)
    {
        if (! $request->has('token') || ! $request->has('paymentId') || ! $request->has('PayerID')) {
            return redirect()->route('wallet.deposit.failed');
        }

        try {
            $paymentService = $paymentService->paymentExecution($request->get('paymentId'), $request->get('PayerID'));
        }
        catch (\Exception $e) {
            \Log::error('Payment failed:: ');
            \Log::info($e->getMessage());

            return redirect()->route(config('wallet.routes.deposit_form'))->with('error', $e->getMessage())->withInput();
        }

        // Payment succeed
        if ($paymentService->success && $paymentService->response) {
            $meta['description'] = trans('wallet::lang.deposit_description',
                ['marketplace' => get_platform_title(), 'payment_method' => 'Paypal']);

            return $this->depositCompleted($paymentService->response->transactions[0]->amount->total, $meta);
        }

        return redirect()->route(config('wallet.routes.deposit_form'))
        ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function paymentFailed(Request $request)
    {
        return redirect()->route(config('wallet.routes.deposit_form'))
        ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    }

    /**
     * @param $amount
     * @param array $meta
     * @param bool $confirm
     * @return RedirectResponse
     */
    private function depositCompleted($amount, $meta = [], $confirm = true)
    {
        $meta = array_merge([
            'type' => Transaction::TYPE_DEPOSIT,
        ], $meta);

        if (Auth::guard('customer')->check()) {

            $trans = Auth::guard('customer')->user()->deposit($amount, $meta, $confirm);

            PayoutJob::dispatch($trans, Deposit::class);

            return redirect()->route(config('wallet.routes.wallet'))
                ->with('success', trans('wallet::lang.payment_success'));
        }

        if (Auth::user()->isMerchant()) {

            $trans = Auth::user()->shop->deposit($amount, $meta, $confirm);
            PayoutJob::dispatch($trans, Deposit::class);

            return redirect()->route(config('wallet.routes.wallet'))
                ->with('success', trans('wallet::lang.payment_success'));
        }

        return redirect()->route(config('wallet.routes.wallet'))
        ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    }

    /**
     * Paystack success:
     * @param Request $request
     * @param PaystackPaymentService $paymentService
     * @return RedirectResponse
     */
    public function paystackPaymentSuccess(Request $request, PaystackPaymentService $paymentService)
    {
        if ($request->has('trxref') && $request->has('reference'))
        {
            $response = $paymentService->verify($request->reference);

            if ($response->status){

                $meta['description'] = trans('wallet::lang.deposit_description',
                    ['marketplace' => get_platform_title(), 'payment_method' => "Paystack"]);

                return $this->depositCompleted(get_doller_from_cent($response->data->amount), $meta);

            }

        }

        return redirect()->route('wallet.routes.deposit_form')
            ->with('error', trans('wallet::lang.payment_failed'))->withInput();

    }

}