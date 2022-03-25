<?php

namespace Incevio\Package\Wallet\Http\Controllers\Admin;

use App\Shop;
use App\Helpers\ListHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Incevio\Package\Wallet\Jobs\PayoutJob;
use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Notifications\Created;
use Incevio\Package\Wallet\Services\CommonService;
use Incevio\Package\Wallet\Http\Requests\PayoutRequest;

class PayoutController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $payouts = Transaction::payouts()->complete()->orderBy('created_at', 'desc')->get();

        return view('wallet::admin.payouts', compact('payouts'));
    }

    public function show_form(Request $request)
    {
        $shops = ListHelper::shops();

        return view('wallet::admin._payout', compact('shops'));
    }

    public function payout(PayoutRequest $request, Transaction $transaction)
    {
        try {
            $meta = [
                'type' => $transaction::TYPE_PAYOUT,
                'description' => trans('wallet::lang.payout_desc', ['platform' => get_platform_title()]),
                'fee' => $request->fee
            ];

            $amount = ($request->amount + $request->fee);
            $trans = Shop::find($request->shop_id)->withdraw($amount, $meta, true, true);

            //Dispatch Job
            PayoutJob::dispatch($trans, Created::class);

            return redirect()->back()->with('success', trans('wallet::lang.payout_approved'));
        }
        catch (\Exception $exception){
            return redirect()->back()->with('warning', $exception->getMessage());
        }
    }


}
