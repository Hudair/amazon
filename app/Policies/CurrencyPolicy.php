<?php

namespace App\Policies;

use App\Models\Currency;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurrencyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view currencys.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'view_utility'))->check();
    }

    /**
     * Determine whether the user can view the Currency.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Currency  $currency
     * @return mixed
     */
    public function view(User $user, Currency $currency)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'view_utility', $currency))->check();
    }

    /**
     * Determine whether the user can create Currencys.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'add_utility'))->check();
    }

    /**
     * Determine whether the user can update the Currency.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Currency  $currency
     * @return mixed
     */
    public function update(User $user, Currency $currency)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'edit_utility', $currency))->check();
    }

    /**
     * Determine whether the user can delete the Currency.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Currency  $currency
     * @return mixed
     */
    public function delete(User $user, Currency $currency)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'delete_utility', $currency))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'delete_utility'))->check();
    }
}
