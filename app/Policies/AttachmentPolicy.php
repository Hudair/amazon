<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the Attachment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attachment  $attachment
     * @return mixed
     */
    public function delete(User $user, Attachment $attachment)
    {
        return $user->isAdmin() || $attachment->owner->id == $user->id;
    }
}
