<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Shop;
use App\Models\ChatConversation;
use App\Events\Chat\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\ChatConversationRequest;
use App\Http\Requests\Validations\SaveChatConversationRequest;
use Illuminate\Support\Facades\Auth;


// use Illuminate\Broadcasting\InteractsWithSockets;
// use App\Http\Requests\Validations\OrderDetailRequest;

class ChatController extends Controller
{
    /**
     * Show feedback form.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function conversation(ChatConversationRequest $request, Shop $shop)
    {
        $conversation = ChatConversation::where([
            'customer_id' => Auth::guard('customer')->id(),
            'shop_id' => $shop->id,
        ])->with('replies')->first();

        return response($conversation);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function save(SaveChatConversationRequest $request)
    {
        $shop = Shop::where('slug', $request->shop_slug)->first();

        if (!$shop) {
            return response(trans('responses.404'), 404);
        }

        $conversation = ChatConversation::where([
            'customer_id' => $request->customer_id,
            'shop_id' => $shop->id
        ])->first();

        if ($conversation) {
            $conversation->markAsUnread();
            $msg_object = $conversation->replies()->create([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'reply' => $request->message,
            ]);
        } elseif ($request->customer_id) {
            $msg_object = ChatConversation::create([
                'shop_id' => $shop->id,
                'customer_id' => $request->customer_id,
                'message' => $request->message,
                'status' => ChatConversation::STATUS_NEW,
            ]);
        } else {
            return response(trans('responses.unauthorized'), 401);
        }

        event(new NewMessageEvent($msg_object, $request->message));

        // if ($request->ajax()) {
        return response(trans('responses.success'), 200);
        // }
    }
}
