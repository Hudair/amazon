<?php

namespace Incevio\Package\Wallet\Http\Controllers\Admin;

use Auth;
use App\PaymentMethod;
use App\Helpers\ListHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Incevio\Package\Wallet\Models\Transaction;

class WalletSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentMethods = ListHelper::payment_methods(True);

        return view('wallet::admin.settings', compact('paymentMethods'));
    }
}
