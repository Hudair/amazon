<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Wishlist;
use Illuminate\Auth\Access\HandlesAuthorization;

class WishlistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Customer can remove the Wishlist.
     *
     * @param  \App\Models\Customer  $customer
     * @param  \App\Models\Wishlist  $wishlist
     * @return bool
     */
    public function remove(Customer $customer, Wishlist $wishlist)
    {
        return $wishlist->customer_id === $customer->id;
    }
}
