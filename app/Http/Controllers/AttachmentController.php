<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Http\Requests\Validations\DeleteAttachmentRequest;
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

        return back()->with('error', trans('messages.file_not_exist'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request    $request
     * @param  Attachment $attachment
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteAttachmentRequest $request, Attachment $attachment)
    {
        if (Storage::exists($attachment->path)) {
            Storage::delete($attachment->path);
        }

        if ($attachment->forceDelete()) {
            return back()->with('success', trans('messages.file_deleted'));
        }

        return back()->with('error', trans('messages.failed'));
    }
}
