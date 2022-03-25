<?php

namespace Incevio\Package\Wallet\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Incevio\Package\Wallet\Http\Requests\WithdrawalRequest;
use Incevio\Package\Wallet\Jobs\PayoutJob;
use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Notifications\Pending;

class WithdrawalController extends Controller
{
    public function show_form(Request $request)
    {
        $minimum = get_min_withdrawal_limit();
        $balance = Auth::user()->shop->balance;

        // Auth::user()->shop->deposit(rand(5,999));

        return view('wallet::_withdraw', compact('balance','minimum'));
    }

    public function withdraw(WithdrawalRequest $request)
    {
        $meta = [
            'type' => Transaction::TYPE_PAYOUT,
            'description' => trans('wallet::lang.payout_requested'),
        ];

        $transaction = Auth::user()->shop->withdraw($request->amount, $meta, false, false);

        PayoutJob::dispatch($transaction, Pending::class);

        return redirect()->route('account.wallet')->with('success', trans('wallet::lang.payout_requested'));
    }


}
