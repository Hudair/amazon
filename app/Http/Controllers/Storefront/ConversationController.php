<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Message;
use App\Models\Order;
use App\Models\Reply;
use App\Models\Shop;
use App\Events\Message\MessageReplied;
use App\Events\Message\NewMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\ArchiveMessageRequest;
use App\Http\Requests\Validations\ContactSellerRequest;
use App\Http\Requests\Validations\OrderConversationRequest;
use App\Http\Requests\Validations\ReplyMyMessageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Contact seller.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function contact(ContactSellerRequest $request, $slug)
    {
        $shop = Shop::select(['id'])->where('slug', $slug)->active()->first();

        if (!$shop) {
            return redirect()->back()->with('error', trans('theme.notify.store_not_available'));
        }

        $message = new Message([
            'customer_id' => Auth::guard('customer')->user()->id,
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'product_id' => $request->input('product_id'),
            'customer_status' => Message::STATUS_READ,
        ]);

        $shop->messages()->save($message);

        event(new NewMessage($message));

        return redirect()->back()->with('success', trans('theme.notify.message_sent'));
    }

    public function show(Message $message)
    {
        $tab = 'message';
        $$tab = $message;

        $message->markAsRead();

        return view('theme::dashboard', compact('tab', $tab));
    }

    /**
     * Start a order conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function reply(ReplyMyMessageRequest $request, Message $message)
    {
        $reply = $message->replies()->create($request->all());

        // Update parent message
        $message->hasNewReply();

        event(new MessageReplied($reply));

        return redirect()->back()->with('success', trans('theme.notify.message_sent'));
    }

    /**
     * Start a order conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     *
     * @return \Illuminate\Http\Response
     */
    public function order_conversation(OrderConversationRequest $request, Order $order)
    {
        $user_id = Auth::user()->id;

        if ($order->conversation) {
            $msg = new Reply;
            $msg->reply = $request->input('message');

            if (Auth::guard('customer')->check()) {
                $msg->customer_id = $user_id;
            } else {
                $msg->user_id = $user_id;
            }

            $reply = $order->conversation->replies()->save($msg);

            // Update parent message
            $order->conversation->update([
                'status' => Message::STATUS_NEW,
                'label' => Message::LABEL_INBOX,
            ]);

            event(new MessageReplied($reply));
        } else {
            $msg = new Message;
            $msg->message = $request->input('message');
            $msg->shop_id = $order->shop_id;

            if (Auth::guard('customer')->check()) {
                $msg->subject = trans('theme.defaults.new_message_from', ['sender' => Auth::user()->getName()]);
                $msg->customer_id = $user_id;
            } else {
                $msg->user_id = $user_id;
            }

            $conversation = $order->conversation()->save($msg);

            event(new NewMessage($conversation));
        }

        // Update the order if goods_received
        if ($request->has('goods_received')) {
            $order->mark_as_goods_received();
        }

        if ($request->hasFile('photo')) {
            $msg->saveAttachments($request->file('photo'));
        }

        return back()->with('success', trans('theme.notify.message_sent'));
    }

    /**
     * archive message convesation
     *
     * @param  Request $request
     * @param  Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function archive(ArchiveMessageRequest $request, Message $message)
    {
        $message->archive();

        return back()->with('success', trans('theme.message_archived'));
    }
}
