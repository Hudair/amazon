<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChatConversation;
use App\Common\Authorizable;
use App\Events\Chat\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\SaveChatConversationRequest;
use App\Http\Requests\Validations\ViewChatConversationRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use Authorizable;

    /**
     * Show feedback form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.chat.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\ChatConversation   $chat
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ViewChatConversationRequest $request, ChatConversation $chat)
    {
        $chat->markAsRead();

        return view('admin.chat._chat_conversation', compact('chat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reply(SaveChatConversationRequest $request, ChatConversation $chat)
    {
        $reply = $chat->replies()->create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user_id,
            'reply' => $request->message,
        ]);

        event(new NewMessageEvent($reply, $request->message));

        if ($request->ajax()) {
            return response(trans('responses.success'), 200);
        }

        return back();
    }
}
