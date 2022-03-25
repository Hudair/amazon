<?php

namespace Incevio\Package\Wallet\Http\Controllers\Storefront;

use Auth;
use App\Customer;
use App\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Contracts\PaymentServiceContract;
use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Services\CommonService;
use Incevio\Package\Wallet\Http\Requests\DepositRequest;
use Incevio\Package\Wallet\Http\Requests\TransferRequest;
use App\Services\Payments\PaypalExpressPaymentService;
use App\Services\Payments\PaystackPaymentService;
//use function GuzzleHttp\Promise\all;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    // 	$wallet = Auth::guard('customer')->user();

    //     $tab = 'wallet';

    //     // View loaded from theme directory, need to do in better ways
    //     $content = view('packages._wallet', compact('wallet'))->render();

    //     return view('dashboard', compact('tab','content'));
    // }


    /**
     *  Show Deposit Form
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    // public function show_deposit_form(Request $request)
    // {
    //     $tab = 'wallet';
    //     $paymentMethods = $paymentMethods = PaymentMethod::find(get_from_option_table('wallet_payment_methods', []));
    //     // View loaded from theme directory, need to do in better ways
    //     $content = view('packages.deposit', compact('paymentMethods'))->render();

    //     return view('dashboard', compact('tab','content'));
    // }

    /**
     * Deposit Via Customer.
     * @param DepositRequest $request
     * @param PaymentServiceContract $paymentService
     * @return RedirectResponse
     */
    // public function deposit(DepositRequest $request, PaymentServiceContract $paymentService)
    // {
    //     try {
    //         $result = $paymentService->setReceiver('platform')
    //             ->setAmount($request->amount)
    //             ->setDescription(trans('wallet::lang.deposit_description',
    //                 ['marketplace' => get_platform_title(), 'payment_method' => $request->payment_method]))
    //             ->setConfig()
    //             ->charge();

    //         // Check if the result is a RedirectResponse of Paypal and some other gateways
    //         if($result instanceof RedirectResponse) {
    //             return $result;
    //         }
    //     }
    //     catch (\Exception $e) {
    //         \Log::error('Payment failed:: ');
    //         \Log::info($e);

    //         return redirect()->back(config('my.wallet'))
    //             ->with('error', $e->getMessage())->withInput();
    //         // ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    //     }

    //     // Payment succeed
    //     if ($result->success) {
    //         $meta['description'] = trans('wallet::lang.deposit_description',
    //             ['marketplace' => get_platform_title(), 'payment_method' => $request->payment_method]);

    //         return $this->depositCompleted($request->amount, $meta, true);
    //     }

    //     return redirect()->back()->with('error', trans('wallet::lang.payment_failed'))->withInput();
    // }

    /**
     * Show Transfer Form
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    // public function show_transfer_form(Request $request)
    // {
    //     $tab = 'wallet';
    //     //$paymentMethods = ListHelper::payment_methods();
    //     // View loaded from theme directory, need to do in better ways
    //     $content = view('packages._transfer')->render();

    //     return view('dashboard', compact('tab','content'));
    // }

    /**
     * Transfer balance to Other Customer
     * @param TransferRequest $request
     * @return RedirectResponse
     */
    // public function transfer(TransferRequest $request)
    // {
    //     $customer = Customer::where('email', $request->email)->first();

    //     if (! $customer instanceof Customer){
    //         return redirect()->back()->with('warning', trans('wallet::lang.email_not_found'));
    //     }

    //     $meta = self::getMeta($request, $customer);

    //     try {
    //         (new CommonService())->transfer(Auth::user()->wallet, $customer->wallet, $request->amount, $meta);

    //         return redirect()->route('my.wallet')->with('success', trans('wallet::lang.payment_success'));
    //     }
    //     catch (\Exception $exception){
    //         \Log::error('Transfer failed:: ');
    //         \Log::info($exception);

    //         return redirect()->route('my.wallet')
    //             ->with('warning', $exception->getMessage())->withInput();
    //     }
    // }

    /**
     * Invoice
     * @param Transaction $transaction
     */
    // public function invoice(Transaction $transaction)
    // {
    //     return $transaction->customerInvoice('d');
    // }

    /**
     * get Formatted meta:
     * @param $request
     * @param $customer
     * @return array[]
     */
    // public function getMeta($request, $customer)
    // {
    //     return [
    //         'from' => [
    //             'type' => Transaction::TYPE_WITHDRAW,
    //             'to' => $customer->email,
    //             'description' => "Balance transferred to " . $request->email
    //         ],
    //         'to' => [
    //             'type' => Transaction::TYPE_DEPOSIT,
    //             'from' => Auth::user()->email,
    //             'description' => "Balance Received from " . Auth::user()->email
    //         ]
    //     ];
    // }

    /**
     * @param $amount
     * @param array $meta
     * @param bool $confirm
     * @return RedirectResponse
     */
    // private function depositCompleted($amount, $meta = [], $confirm = true)
    // {
    //     $meta = array_merge([
    //         'type' => Transaction::TYPE_DEPOSIT,
    //     ], $meta);

    //     $trans =  Auth::user()->deposit($amount, $meta, $confirm);

    //     if ($trans) {
    //         //PayoutJob::dispatch($trans, Deposit::class);
    //         return redirect()->route('my.wallet')
    //             ->with('success', trans('wallet::lang.payment_success'));
    //     }

    //     return redirect()->route(config('my.wallet'))
    //         ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    // }

    /**
     * @param Request $request
     * @param PaypalExpressPaymentService $paymentService
     * @return RedirectResponse
     */
    // public function paypalPaymentSuccess(Request $request, PaypalExpressPaymentService $paymentService)
    // {
    //     if (! $request->has('token') || ! $request->has('paymentId') || ! $request->has('PayerID')) {
    //         return redirect()->route('my.wallet')
    //             ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    //     }

    //     try {
    //         $paymentService = $paymentService->paymentExecution($request->get('paymentId'), $request->get('PayerID'));
    //     }
    //     catch (\Exception $e) {
    //         \Log::error('Payment failed:: ');
    //         \Log::info($e->getMessage());

    //         return redirect()->route(config('wallet.routes.deposit_form'))->with('error', $e->getMessage())->withInput();
    //     }

    //     // Payment succeed
    //     if ($paymentService->success && $paymentService->response) {
    //         $meta['description'] = trans('wallet::lang.deposit_description',
    //             ['marketplace' => get_platform_title(), 'payment_method' => 'Paypal']);

    //         return $this->depositCompleted($paymentService->response->transactions[0]->amount->total, $meta);
    //     }

    //     return redirect()->route(config('wallet.routes.deposit_form'))
    //         ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    // }

    /**
     * @param Request $request
     * @param PaystackPaymentService $paymentService
     * @return RedirectResponse
     */
    // public function paystackPaymentSuccess(Request $request, PaystackPaymentService $paymentService)
    // {
    //     if ($request->has('trxref') && $request->has('reference'))
    //     {
    //         $response = $paymentService->verify($request->reference);

    //         if ($response->status){

    //             $meta['description'] = trans('wallet::lang.deposit_description',
    //                 ['marketplace' => get_platform_title(), 'payment_method' => "Paystack"]);
    //             return $this->depositCompleted(get_doller_from_cent($response->data->amount), $meta);

    //         }

    //     }

    //     return redirect()->route('my.wallet')
    //         ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    // }

    /**
     * If payment Failed
     * @param Request $request
     * @return RedirectResponse
     */
    // public function paymentFailed(Request $request)
    // {
    //     return redirect()->route(config('my.wallet'))
    //         ->with('error', trans('wallet::lang.payment_failed'))->withInput();
    // }

}
