<?php

namespace App\Common;

use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;

/**
 * Attach this Trait to a User (or other model) for easier read/writes on Replies
 *
 * @author Munna Khan
 */
trait Attachable
{
    /**
     * Check if model has an attachments.
     *
     * @return bool
     */
    public function hasAttachments()
    {
        return (bool) $this->attachments()->count();
    }

    /**
     * Return collection of attachments related to the attachable
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Return collection of attachments related to the user/customer
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function files()
    {
        return $this->morphMany(Attachment::class, 'ownable');
    }

    /**
     * Save Attachments
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  model $attachable
     *
     * @return attachment model
     */
    public function saveAttachments($attachments)
    {
        $data = [];
        $dir = attachment_storage_dir();

        $ownable = [];
        if (Auth::check()) {
            $ownable['ownable_id'] = Auth::user()->id;
            $ownable['ownable_type'] = Auth::guard('customer')->check() ? \App\Models\Customer::class : \App\Models\User::class;
        }

        if (is_array($attachments)) {
            foreach ($attachments as $order => $file) {
                $data[] = array_merge($this->storeFile($dir, $file), $ownable);
            }
        } else {
            $data[] = array_merge($this->storeFile($dir, $attachments), $ownable);
        }

        return $this->attachments()->createMany($data);
    }

    /**
     * store File one by one
     *
     * @param  string $dir
     * @param  obj $file
     *
     * @return arr
     */
    private function storeFile($dir, $file)
    {
        $path = Storage::put($dir, $file);

        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            // 'name' => Str::slug($file->getClientOriginalName(), '-'),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ];
    }

    /**
     * @param IncomingMail $mail
     * @param $attachable
     */
    // public static function storeAttachmentsFromEmail($mail, $attachable)
    // {
    //     foreach ($mail->getAttachments() as $mailAttachment) {
    //         $path = str_replace(' ', '_', $attachable->id.'_'.$mailAttachment->name);
    //         Storage::put(attachment_storage_path() . $path, file_get_contents($mailAttachment->filePath));
    //         $attachable->attachments()->create(['path' => $path]);
    //         unlink($mailAttachment->filePath);
    //     }
    // }

    /**
     * Deletes the given attachment.
     *
     * @return bool
     */
    public function deleteAttachment($attachment)
    {
        // \Log::info($attachment);
        if (optional($attachment)->path) {
            Storage::delete($attachment->path);

            return $attachment->delete();
        }
    }

    /**
     * Deletes all the attachments of this model.
     *
     * @return bool
     */
    public function flushAttachments()
    {
        foreach ($this->attachments as $attachment) {
            $this->deleteAttachment($attachment);
        }
    }
}
