<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Cancellation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\OrderCancellationRequest;
use App\Http\Requests\Validations\OrderDetailRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderCancellationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('cancelAny', Order::class); // Check permission

        if (Auth::user()->isFromPlatform()) {
            $cancellations = Cancellation::open()
                ->with('shop:id,name', 'customer:id,name', 'order')
                ->orderBy('created_at', 'desc')->get();

            return view('admin.order.approvals', compact('cancellations'));
        }

        $cancellations = Cancellation::mine()->with('customer:id,name', 'order')
            ->orderBy('created_at', 'desc')->get();

        return view('admin.order.cancellations', compact('cancellations'));
    }

    /**
     * Cancel the order and revert the items into available stock
     */
    public function create(OrderDetailRequest $request, Order $order)
    {
        $this->authorize('cancel', $order); // Check permission

        return view('admin.order._cancellation_create', compact('order'));
    }

    public function handleCancellationRequest(OrderDetailRequest $request, Order $order, $action = 'decline')
    {
        $this->authorize('cancel', $order); // Check permission

        // Fail id the cancellation is not open
        if ($order->cancellation->isClosed()) {
            abort(403);
        }

        // Start transaction!
        DB::beginTransaction();
        try {
            if ($action == 'approve') {
                $order->cancellation->approve();
            } else {
                $order->cancellation->decline();
            }
        } catch (\Exception $e) {
            \Log::error($e);        // Log the error

            DB::rollback();         // rollback the transaction and log the error

            return redirect()->back()->with('error', $e->getMessage());
        }

        DB::commit();           // Everything is fine. Now commit the transaction

        return back()->with('success', trans('app.order_updated'));
    }

    /**
     * Cancel the order and revert the items into available stock
     */
    public function cancel(OrderCancellationRequest $request, Order $order)
    {
        $this->authorize('cancel', $order); // Check permission

        // Start transaction!
        DB::beginTransaction();
        try {
            // Vendor cancelling the order and admin approval required
            if (cancellation_require_admin_approval() && Auth::user()->isFromMerchant()) {
                $order->cancellation()
                    ->create(array_merge($request->all(), [
                        'items' => null,
                        'status' => Cancellation::STATUS_OPEN,
                    ]));
            }
            // No approval needed, direct cancel
            else {
                // Check if has a cancellation request
                if ($order->cancellation) {
                    $order->cancellation->forceFill([
                        'items' => null,
                        'status' => Cancellation::STATUS_APPROVED,
                    ])->save();
                }

                $order->cancel(false, $request->cancellation_fee);
            }
        } catch (\Exception $e) {
            \Log::error($e);        // Log the error

            DB::rollback();         // rollback the transaction and log the error

            return redirect()->back()->with('error', trans('messages.failed'));
        }

        DB::commit();           // Everything is fine. Now commit the transaction

        return redirect()->back()->with('success', trans('app.order_updated'));
    }
}
