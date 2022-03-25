<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use App\Models\ChatConversation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\ChatConversationRequest;
use App\Http\Requests\Validations\SaveChatConversationRequest;
// use App\Http\Requests\Validations\OrderDetailRequest;
// use App\Http\Requests\Validations\DirectCheckoutRequest;
// use App\Events\Chat\NewMessageEvent;
use App\Http\Resources\ConversationResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\DB;
// use Symfony\Component\HttpFoundation\File\File;
// use Illuminate\Http\UploadedFile;

class ConversationController extends Controller
{
    /**
     * Show all conversations
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function conversations(Request $request)
    {
        $conversations = ChatConversation::where('customer_id', Auth::guard('api')->id())->get();

        return ConversationResource::collection($conversations);
    }

    /**
     * Show single conversation
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function conversation(ChatConversationRequest $request, Shop $shop)
    {
        $conversation = ChatConversation::where([
            'customer_id' => Auth::guard('api')->id(),
            'shop_id' => $shop->id,
        ])->with('replies')->first();

        if ($conversation) {
            return new ConversationResource($conversation);
        }

        return response()->json([
            'message' => trans('api.welcome_chat'),
        ]);
    }

    /**
     * Save message
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function save_conversation(SaveChatConversationRequest $request, Shop $shop)
    {
        $conversation = ChatConversation::where([
            'customer_id' => $request->customer_id,
            'shop_id' => $shop->id,
        ])->first();

        if ($conversation) {
            $conversation->markAsUnread();
            $msg_object = $conversation->replies()->create([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'reply' => $request->message,
            ]);

            if ($request->has('photo')) {
                $file = create_file_from_base64($request->get('photo'));

                $msg_object->saveAttachments($file);
            }
        } elseif ($request->customer_id) {
            $conversation = ChatConversation::create([
                'shop_id' => $shop->id,
                'customer_id' => $request->customer_id,
                'message' => $request->message,
                'status' => ChatConversation::STATUS_NEW,
            ]);

            if ($request->has('photo')) {
                $file = create_file_from_base64($request->get('photo'));

                $conversation->saveAttachments($file);
            }
        } else {
            return response(trans('responses.unauthorized'), 401);
        }

        // event(new NewMessageEvent($msg_object, $request->message));

        return new ConversationResource($conversation);
    }

    /**
     * Create File from base64 strings:
     */
    /* public function createFileFromBase64($base64File)
    {
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));
        // save it to temporary dir first.
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString().".png";
        file_put_contents($tmpFilePath, $fileData);
        // this just to help us get file info.
        $tmpFile = new File($tmpFilePath);

        $file = new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        return $file;
    }*/
}
