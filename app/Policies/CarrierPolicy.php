<?php

namespace App\Policies;

use App\Models\Carrier;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarrierPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view carriers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_carrier'))->check();
    }

    /**
     * Determine whether the user can view the Carrier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Carrier  $carrier
     * @return mixed
     */
    public function view(User $user, Carrier $carrier)
    {
        return (new Authorize($user, 'view_carrier', $carrier))->check();
    }

    /**
     * Determine whether the user can create Carriers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_carrier'))->check();
    }

    /**
     * Determine whether the user can update the Carrier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Carrier  $carrier
     * @return mixed
     */
    public function update(User $user, Carrier $carrier)
    {
        return (new Authorize($user, 'edit_carrier', $carrier))->check();
    }

    /**
     * Determine whether the user can delete the Carrier.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Carrier  $carrier
     * @return mixed
     */
    public function delete(User $user, Carrier $carrier)
    {
        return (new Authorize($user, 'delete_carrier', $carrier))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_carrier'))->check();
    }
}
