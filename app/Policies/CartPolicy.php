<?php

namespace App\Policies;

use App\Models\Cart;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view carts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_cart'))->check();
    }

    /**
     * Determine whether the user can view the Cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function view(User $user, Cart $cart)
    {
        return (new Authorize($user, 'view_cart', $cart))->check();
    }

    /**
     * Determine whether the user can create Carts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isFromMerchant() ? (new Authorize($user, 'add_cart'))->check() : false;
    }

    /**
     * Determine whether the user can update the Cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function update(User $user, Cart $cart)
    {
        return $user->isFromMerchant() ? (new Authorize($user, 'edit_cart'))->check() : false;
    }

    /**
     * Determine whether the user can delete the Cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function delete(User $user, Cart $cart)
    {
        return (new Authorize($user, 'delete_cart', $cart))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_cart'))->check();
    }
}
