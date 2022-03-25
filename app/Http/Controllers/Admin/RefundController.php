<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Events\Refund\RefundApproved;
use App\Events\Refund\RefundDeclined;
use App\Events\Refund\RefundInitiated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\InitiateRefundRequest;
use App\Repositories\Refund\RefundRepository;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    use Authorizable;

    private $model_name;

    private $refund;

    /**
     * construct
     */
    public function __construct(RefundRepository $refund)
    {
        parent::__construct();

        $this->model_name = trans('app.model.refund');

        $this->refund = $refund;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refunds = $this->refund->open();

        $closed = $this->refund->closed();

        return view('admin.refund.index', compact('refunds', 'closed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function showRefundForm($order = null)
    {
        if ($order) {
            $order = $this->refund->findOrder($order);
        }

        return view('admin.refund._initiate', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initiate(InitiateRefundRequest $request)
    {
        // Start transaction!
        \DB::beginTransaction();
        try {
            $refund = $this->refund->store($request);

            $this->refund_to_wallet($refund);

            event(new RefundInitiated($refund, $request->filled('notify_customer')));
        } catch (\Exception $e) {
            \Log::error($e);        // Log the error

            \DB::rollback();         // rollback the transaction and log the error

            return redirect()->back()->with('error', $e->getMessage());
        }

        \DB::commit();           // Everything is fine. Now commit the transaction

        return back()->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function response($id)
    {
        $refund = $this->refund->find($id);

        return view('admin.refund._response', compact('refund'));
    }

    public function approve(Request $request, $id)
    {
        $refund = $this->refund->approve($id);

        $this->refund_to_wallet($refund);

        event(new RefundApproved($refund, $request->filled('notify_customer')));

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    public function decline(Request $request, $id)
    {
        $refund = $this->refund->decline($id);

        event(new RefundDeclined($refund, $request->filled('notify_customer')));

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    private function refund_to_wallet($refund)
    {
        if ($refund->isApproved() && $refund->order->customer_id && customer_has_wallet()) {
            $wallet = new \Incevio\Package\Wallet\Services\RefundToWallet();

            return $wallet->sender($refund->shop)
                ->receiver($refund->order->customer)
                ->amount($refund->amount)
                ->meta([
                    'type' => trans('wallet::lang.refund'),
                    'description' => trans('wallet::lang.refund_of', ['order' => $refund->order->order_number]),
                ])
                ->forceTransfer()
                ->execute();
        }
    }
}
