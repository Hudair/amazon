<?php

namespace App\Http\Controllers\Storefront;

// use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\System;
use App\Models\Dispute;
use App\Models\DisputeType;
use App\Events\Dispute\DisputeCreated;
use App\Events\Dispute\DisputeSolved;
use App\Events\Dispute\DisputeUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateDisputeRequest;
use App\Http\Requests\Validations\OrderDetailRequest;
// use App\Http\Requests\Validations\RefundRequest;
use App\Http\Requests\Validations\ReplyDisputeRequest;
use App\Notifications\SuperAdmin\DisputeAppealed as DisputeAppealedNotification;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    /**
     * show_dispute_form
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function show_dispute_form(OrderDetailRequest $request, Order $order)
    {
        $types = DisputeType::orderBy('id')->pluck('detail', 'id');

        return view('theme::dispute', compact('order', 'types'));
    }

    /**
     * refund_request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    // public function refund_request(RefundRequest $request, Order $order)
    // {
    //     $refund = $order->refunds()->create($request->all());

    //     // event(new RefundCreated($refund));

    //     return redirect()->route('order.detail', $order)->with('success', trans('theme.notify.refund_request_sent'));
    // }

    /**
     * open_dispute
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function open_dispute(CreateDisputeRequest $request, Order $order)
    {
        $dispute = $order->dispute()->create($request->all());

        event(new DisputeCreated($dispute));

        return redirect()->route('order.detail', $order)
            ->with('success', trans('theme.notify.dispute_created'));
    }

    /**
     * [response description]
     *
     * @param  ReplyDisputeRequest $request [description]
     * @param  Dispute             $dispute [description]
     *
     * @return [type]                       [description]
     */
    public function response(ReplyDisputeRequest $request, Dispute $dispute)
    {
        // Update status
        // if ($dispute->status != $request->status) {
        //     $dispute->status = $request->status;
        //     $dispute->save();
        // }

        $response = $dispute->replies()->create($request->all());

        if ($request->hasFile('attachments')) {
            $response->saveAttachments($request->file('attachments'));
        }

        if ($request->get('solved')) {
            $this->markAsSolved($request, $dispute);
        } else {
            event(new DisputeUpdated($response));
        }

        return back()->with('success', trans('theme.notify.dispute_updated'));
    }

    /**
     * [appeal description]
     *
     * @param  ReplyDisputeRequest $request [description]
     * @param  Dispute             $dispute [description]
     *
     * @return [type]                       [description]
     */
    public function appeal(ReplyDisputeRequest $request, Dispute $dispute)
    {
        $dispute->status = Dispute::STATUS_APPEALED;
        $dispute->save();

        $response = $dispute->replies()->create($request->all());

        if ($request->hasFile('attachments')) {
            $response->saveAttachments($request->file('attachments'));
        }

        // Send notification to Admin
        if (config('system_settings.notify_when_dispute_appealed')) {
            $system = System::orderBy('id', 'asc')->first();
            $system->superAdmin()->notify(new DisputeAppealedNotification($response));
        }

        event(new DisputeUpdated($response));

        return back()->with('success', trans('theme.notify.dispute_updated'));
    }

    public function markAsSolved(Request $request, Dispute $dispute)
    {
        $dispute->status = Dispute::STATUS_SOLVED;

        $dispute->save();

        event(new DisputeSolved($dispute));

        return back()->with('success', trans('theme.notify.dispute_updated'));
    }
}
