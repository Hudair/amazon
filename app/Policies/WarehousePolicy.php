<?php

namespace App\Policies;

use App\Helpers\Authorize;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view warehouses.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_warehouse'))->check();
    }

    /**
     * Determine whether the user can view the Warehouse.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function view(User $user, Warehouse $warehouse)
    {
        return (new Authorize($user, 'view_warehouse', $warehouse))->check();
    }

    /**
     * Determine whether the user can create Warehouses.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_warehouse'))->check();
    }

    /**
     * Determine whether the user can update the Warehouse.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function update(User $user, Warehouse $warehouse)
    {
        return (new Authorize($user, 'edit_warehouse', $warehouse))->check();
    }

    /**
     * Determine whether the user can delete the Warehouse.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function delete(User $user, Warehouse $warehouse)
    {
        return (new Authorize($user, 'delete_warehouse', $warehouse))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_warehouse'))->check();
    }
}
