<?php

namespace App\Policies;

use App\Helpers\Authorize;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view orders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_order'))->check();
    }

    /**
     * Determine whether the user can view the Order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return (new Authorize($user, 'view_order', $order))->check();
    }

    /**
     * Determine whether the user can create Orders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isFromMerchant() ? (new Authorize($user, 'add_order'))->check() : false;
    }

    /**
     * Determine whether the user can fulfill the Order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function fulfill(User $user, Order $order)
    {
        return (new Authorize($user, 'fulfill_order', $order))->check();
    }

    /**
     * Determine whether the user can view orders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function cancelAny(User $user)
    {
        return (new Authorize($user, 'cancel_order'))->check();
    }

    /**
     * Determine whether the user can view orders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function cancel(User $user, Order $order)
    {
        return (new Authorize($user, 'cancel_order', $order))->check();
    }

    /**
     * Determine whether the user can archived the Order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function archive(User $user, Order $order)
    {
        return (new Authorize($user, 'archive_order', $order))->check();
    }

    /**
     * Determine whether the user can delete the Packaging.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        return (new Authorize($user, 'delete_order', $order))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDestroy(User $user)
    {
        return (new Authorize($user, 'delete_order'))->check();
    }
}
