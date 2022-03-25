<?php

namespace App\Policies;

use App\Models\Attribute;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttributePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view attributes.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_attribute'))->check();
    }

    /**
     * Determine whether the user can view the Attribute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attribute  $attribute
     * @return mixed
     */
    public function view(User $user, Attribute $attribute)
    {
        return (new Authorize($user, 'view_attribute', $attribute))->check();
    }

    /**
     * Determine whether the user can create Attributes.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_attribute'))->check();
    }

    /**
     * Determine whether the user can update the Attribute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attribute  $attribute
     * @return mixed
     */
    public function update(User $user, Attribute $attribute)
    {
        return (new Authorize($user, 'edit_attribute', $attribute))->check();
    }

    /**
     * Determine whether the user can delete the Attribute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attribute  $attribute
     * @return mixed
     */
    public function delete(User $user, Attribute $attribute)
    {
        return (new Authorize($user, 'delete_attribute', $attribute))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_attribute'))->check();
    }
}
