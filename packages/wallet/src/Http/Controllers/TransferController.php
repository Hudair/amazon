<?php

namespace Incevio\Package\Wallet\Http\Controllers;

use App\Shop;
use Auth;
use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Services\CommonService;
use Incevio\Package\Wallet\Http\Requests\TransferRequest;

class TransferController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function show_form(Request $request)
    {

        if (Auth::guard('customer')->check()) {
            $tab = 'wallet';
            $content = view('wallet::customer._transfer')->render();
            return view('theme::dashboard', compact('tab', 'content'));
        }

        if (Auth::user()->isMerchant()){
            return view('wallet::_transfer');
        }

    }

    /**
     * Transfer balance to Other Customer
     * @param TransferRequest $request
     * @return RedirectResponse
     */
    public function transfer(TransferRequest $request)
    {

        $fromWallet = null;
        $toWallet = null;

        if (Auth::guard('customer')->check()) {

            $toWallet = Customer::where('email', $request->email)->first();
            $fromWallet = Auth::guard('customer')->user();

            if (!$toWallet instanceof Customer) {
                return redirect()->back()->with('warning', trans('wallet::lang.email_not_found'));
            }

            $meta = self::getMeta($request, $toWallet, $fromWallet);

        }

        if (Auth::user()){

            $toWallet = Shop::where('email', $request->email)->first();
            $fromWallet = Auth::user()->shop;

            if (!$toWallet instanceof Shop) {
                return redirect()->back()->with('warning', trans('wallet::lang.email_not_found'));
            }

            $meta = self::getMeta($request, $toWallet, $fromWallet);

        }

        try {
            (new CommonService())->transfer($fromWallet->wallet, $toWallet->wallet, $request->amount, $meta);

            return redirect()->route('account.wallet')->with('success', trans('wallet::lang.transfer_success'));
        }
        catch (\Exception $exception){
            \Log::error('Transfer failed:: ');
            \Log::info($exception);

            return redirect()->route('account.wallet')
                ->with('warning', $exception->getMessage())->withInput();
        }
    }

    /**
     * Invoice
     * @param Transaction $transaction
     */
   /* public function invoice(Transaction $transaction)
    {
        return $transaction->customerInvoice('d');
    }*/
    /**
     * get Formatted meta:
     * @param $request
     * @param $customer
     * @return array[]
     */
    public function getMeta($request, $to, $from)
    {
        return [
            'from' => [
                'type' => Transaction::TYPE_WITHDRAW,
                'to' => $to->email,
                'description' => "Balance transferred to " . $request->email
            ],
            'to' => [
                'type' => Transaction::TYPE_DEPOSIT,
                'from' => $from->email,
                'description' => "Balance Received from " . $from->email
            ]
        ];
    }


}
