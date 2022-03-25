<?php

namespace App\Policies;

use App\Helpers\Authorize;
use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view Pages.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_page'))->check();
    }

    /**
     * Determine whether the user can view the Page.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function view(User $user, Page $page)
    {
        return (new Authorize($user, 'view_page', $page))->check();
    }

    /**
     * Determine whether the user can create Pages.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_page'))->check();
    }

    /**
     * Determine whether the user can update the Page.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function update(User $user, Page $page)
    {
        return (new Authorize($user, 'edit_page', $page))->check();
    }

    /**
     * Determine whether the user can delete the Page.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function delete(User $user, Page $page)
    {
        return (new Authorize($user, 'delete_page', $page))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_page'))->check();
    }
}
