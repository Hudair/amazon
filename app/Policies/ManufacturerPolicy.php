<?php

namespace App\Policies;

use App\Helpers\Authorize;
use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManufacturerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view manufacturers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_manufacturer'))->check();
    }

    /**
     * Determine whether the user can view the Manufacturer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return mixed
     */
    public function view(User $user, Manufacturer $manufacturer)
    {
        return (new Authorize($user, 'view_manufacturer', $manufacturer))->check();
    }

    /**
     * Determine whether the user can create Manufacturers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_manufacturer'))->check();
    }

    /**
     * Determine whether the user can update the Manufacturer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return mixed
     */
    public function update(User $user, Manufacturer $manufacturer)
    {
        return (new Authorize($user, 'edit_manufacturer', $manufacturer))->check();
    }

    /**
     * Determine whether the user can delete the Manufacturer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return mixed
     */
    public function delete(User $user, Manufacturer $manufacturer)
    {
        return (new Authorize($user, 'delete_manufacturer', $manufacturer))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_manufacturer'))->check();
    }
}
