<?php

namespace App\Policies;

use App\Models\Blog;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view Blogs.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Blog  $blog
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_utility'))->check();
    }

    /**
     * Determine whether the user can view the Blog.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Blog  $blog
     * @return mixed
     */
    public function view(User $user, Blog $blog)
    {
        return (new Authorize($user, 'view_utility', $blog))->check();
    }

    /**
     * Determine whether the user can create Blogs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_utility'))->check();
    }

    /**
     * Determine whether the user can update the Blog.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Blog  $blog
     * @return mixed
     */
    public function update(User $user, Blog $blog)
    {
        return (new Authorize($user, 'edit_utility', $blog))->check();
    }

    /**
     * Determine whether the user can delete the Blog.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Blog  $blog
     * @return mixed
     */
    public function delete(User $user, Blog $blog)
    {
        return (new Authorize($user, 'delete_utility', $blog))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_utility'))->check();
    }
}
