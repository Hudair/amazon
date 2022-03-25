<?php

namespace App\Http\Controllers\Api;

use App\Models\Attachment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * download attachment file
     *
     * @param  Request    $request
     * @param  Attachment $attachment
     *
     * @return file
     */
    public function download(Request $request, Attachment $attachment)
    {
        if (Storage::exists($attachment->path)) {
            return Storage::download($attachment->path, $attachment->name);
        }

        return response()->json(['message' => trans('messages.file_not_exist')], 404);
    }
}
