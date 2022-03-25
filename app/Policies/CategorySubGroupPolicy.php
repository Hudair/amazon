<?php

namespace App\Policies;

use App\Models\CategorySubGroup;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategorySubGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view category_sub_groups.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_category_sub_group'))->check();
    }

    /**
     * Determine whether the user can view the CategorySubGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CategorySubGroup  $categorySubGroup
     * @return mixed
     */
    public function view(User $user, CategorySubGroup $categorySubGroup)
    {
        return (new Authorize($user, 'view_category_sub_group', $categorySubGroup))->check();
    }

    /**
     * Determine whether the user can create CategorySubGroups.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_category_sub_group'))->check();
    }

    /**
     * Determine whether the user can update the CategorySubGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CategorySubGroup  $categorySubGroup
     * @return mixed
     */
    public function update(User $user, CategorySubGroup $categorySubGroup)
    {
        return (new Authorize($user, 'edit_category_sub_group', $categorySubGroup))->check();
    }

    /**
     * Determine whether the user can delete the CategorySubGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CategorySubGroup  $categorySubGroup
     * @return mixed
     */
    public function delete(User $user, CategorySubGroup $categorySubGroup)
    {
        return (new Authorize($user, 'delete_category_sub_group', $categorySubGroup))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_category_sub_group'))->check();
    }
}
