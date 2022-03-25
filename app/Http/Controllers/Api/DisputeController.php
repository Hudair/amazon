<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\System;
use App\Models\Dispute;
use App\Events\Dispute\DisputeCreated;
use App\Events\Dispute\DisputeSolved;
use App\Events\Dispute\DisputeUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateDisputeRequest;
use App\Http\Requests\Validations\DisputeDetailRequest;
use App\Http\Requests\Validations\OrderDetailRequest;
use App\Http\Requests\Validations\ReplyDisputeRequest;
use App\Http\Resources\DisputeFormResource;
use App\Http\Resources\DisputeLightResource;
use App\Http\Resources\DisputeResource;
use App\Http\Resources\OrderResource;
use App\Notifications\SuperAdmin\DisputeAppealed as DisputeAppealedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $disputes = Auth::guard('api')->user()->disputes()
            ->with(['shop:id,name,slug', 'order.inventories:product_id,slug', 'order.inventories.image'])
            ->paginate(config('mobile_app.view_listing_per_page', 8));

        return DisputeLightResource::collection($disputes);
    }

    /**
     * show_dispute_form
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function create(OrderDetailRequest $request, Order $order)
    {
        return new DisputeFormResource($order);
    }

    /**
     * open_dispute
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDisputeRequest $request, Order $order)
    {
        $dispute = $order->dispute()->create($request->all());

        event(new DisputeCreated($dispute));

        return new DisputeResource($dispute);
    }

    /**
     * show dispute detail
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Dispute   $dispute
     *
     * @return \Illuminate\Http\Response
     */
    public function show(DisputeDetailRequest $request, Dispute $dispute)
    {
        return new DisputeResource($dispute->load('shop:id,name,slug'));
    }

    /**
     * show response_form
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   Dispute  $dispute
     *
     * @return \Illuminate\Http\Response
     */
    public function response_form(DisputeDetailRequest $request, Dispute $dispute)
    {
        return [
            'dispute' => $dispute,
            'statuses' => \App\Helpers\ListHelper::dispute_statuses(),
        ];
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
        if ($dispute->status != $request->status) {
            $dispute->status = $request->status;

            $dispute->save();
        }

        $response = $dispute->replies()->create($request->all());

        if ($request->hasFile('attachments')) {
            $response->saveAttachments($request->file('attachments'));
        }

        event(new DisputeUpdated($response));

        return new DisputeResource($dispute->load('shop:id,name,slug'));
    }

    public function mark_as_solved(DisputeDetailRequest $request, Dispute $dispute)
    {
        $dispute->status = Dispute::STATUS_SOLVED;

        $dispute->save();

        event(new DisputeSolved($dispute));

        return response()->json(trans('theme.notify.dispute_updated'), 200);
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

        return new DisputeResource($dispute->load('shop:id,name,slug'));
    }
}
