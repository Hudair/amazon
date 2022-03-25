<?php

namespace App\Policies;

use App\Helpers\Authorize;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view Messagees.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_message'))->check();
    }

    /**
     * Determine whether the user can view the Message.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return mixed
     */
    public function view(User $user, Message $message)
    {
        return (new Authorize($user, 'view_message', $message))->check();
    }

    /**
     * Determine whether the user can update the Message.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return mixed
     */
    public function update(User $user, Message $message)
    {
        return (new Authorize($user, 'update_message', $message))->check();
    }

    /**
     * Determine whether the user can reply the Message.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return mixed
     */
    public function reply(User $user, Message $message)
    {
        return (new Authorize($user, 'reply_message', $message))->check();
    }

    /**
     * Determine whether the user can delete the Message.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return mixed
     */
    public function delete(User $user, Message $message)
    {
        return (new Authorize($user, 'delete_message', $message))->check();
    }
}
