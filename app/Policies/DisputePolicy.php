<?php

namespace App\Policies;

use App\Models\Dispute;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DisputePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view disputees.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_dispute'))->check();
    }

    /**
     * Determine whether the user can view the dispute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dispute  $dispute
     * @return mixed
     */
    public function view(User $user, Dispute $dispute)
    {
        return (new Authorize($user, 'view_dispute', $dispute))->check();
    }

    /**
     * Determine whether the user can response the dispute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dispute  $dispute
     * @return mixed
     */
    public function response(User $user, Dispute $dispute)
    {
        return (new Authorize($user, 'response_dispute', $dispute))->check();
    }

    /**
     * Determine whether the user can reply the Ticket.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dispute  $dispute
     * @return mixed
     */
    public function storeResponse(User $user, Dispute $dispute)
    {
        return (new Authorize($user, 'response_dispute', $dispute))->check();
    }
}
