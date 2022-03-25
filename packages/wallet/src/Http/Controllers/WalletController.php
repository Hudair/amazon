<?php

namespace Incevio\Package\Wallet\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Incevio\Package\Wallet\Models\Transaction;

class WalletController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::guard('customer')->check()) {
            $wallet = Auth::guard('customer')->user();
            $tab = 'wallet';
            // View loaded from package
            $content = view('wallet::customer._wallet', compact('wallet'))->render();

            //Return to theme View:
            return view('theme::dashboard', compact('tab', 'content'));
        }

        if (Auth::user()->isMerchant()){
            $wallet = Auth::user()->shop;

            return view('wallet::index', compact('wallet'));
        }
    }

    /**
     * Download pdf invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice(Transaction $transaction)
    {
        if (Auth::guard('customer')->check()) {
            return $transaction->customerInvoice('D');
        }

        return $transaction->invoice('D');
    }

}
