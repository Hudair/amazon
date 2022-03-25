<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Shop;
use App\Models\Inventory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\ProductFeedbackCreateRequest;
use App\Http\Requests\Validations\ShopFeedbackCreateRequest;
use App\Http\Resources\FeedbackResource;
// use App\Http\Resources\OrderResource;
// use App\Http\Resources\ConversationResource;
// use App\Http\Requests\Validations\OrderDetailRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * [show_shop_feedbacks description]
     *
     * @param  Request $request [description]
     * @param  [type]  $slug    [description]
     *
     * @return [type]           [description]
     */
    public function show_shop_feedbacks(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->firstOrFail();

        return FeedbackResource::collection($shop->feedbacks()->paginate());
    }

    /**
     * [show_item_feedbacks description]
     *
     * @param  Request $request [description]
     * @param  [type]  $slug    [description]
     *
     * @return [type]           [description]
     */
    public function show_item_feedbacks(Request $request, $slug)
    {
        $item = Inventory::where('slug', $slug)->firstOrFail();

        return FeedbackResource::collection($item->feedbacks()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Order   $order
     * @return \Illuminate\Http\Response
     */
    public function save_shop_feedbacks(ShopFeedbackCreateRequest $request, Order $order)
    {
        if ($order->feedback_id) {
            return response()->json([
                'message' => trans('api.you_already_gave_feedback')
            ], 200);
        }

        $feedback = $order->shop->feedbacks()->create($request->all());
        $order->feedback_given($feedback->id);

        return response()->json(['message' => trans('api.your_feedback_saved')], 200);
        // return new OrderResource($order);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Order   $order
     * @return \Illuminate\Http\Response
     */
    public function save_product_feedbacks(ProductFeedbackCreateRequest $request, Order $order)
    {
        $inputs = $request->input('items');
        $customer_id = Auth::guard('api')->user()->id; //Set customer_id

        foreach ($order->inventories as $inventory) {
            // Skip the item that is not present or given feedback before
            if (!isset($inputs[$inventory->id]) || $inventory->pivot->feedback_id) {
                continue;
            }

            $feedback_data = $inputs[$inventory->id];
            $feedback_data['customer_id'] = $customer_id;

            $feedback = $inventory->feedbacks()->create($feedback_data);

            // Update feedback_id in order_items table
            DB::table('order_items')->where('order_id', $inventory->pivot->order_id)
                ->where('inventory_id', $inventory->id)->update(['feedback_id' => $feedback->id]);
        }

        return response()->json(['message' => trans('api.your_feedback_saved')], 200);
        // return new OrderResource($order);
    }
}
