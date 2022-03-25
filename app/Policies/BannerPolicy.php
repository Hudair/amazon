<?php

namespace App\Policies;

use App\Models\Banner;
use App\Helpers\Authorize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BannerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view Banners.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banner  $banner
     * @return mixed
     */
    // public function index(User $user)
    // {
    //     return (new Authorize($user, 'customize_appearance'))->check();
    // }

    /**
     * Determine whether the user can view the Banner.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banner  $banner
     * @return mixed
     */
    // public function view(User $user, Banner $banner)
    // {
    //     return (new Authorize($user, 'customize_appearance', $banner))->check();
    // }

    /**
     * Determine whether the user can create Banners.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'customize_appearance'))->check();
    }

    /**
     * Determine whether the user can update the Banner.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banner  $banner
     * @return mixed
     */
    public function update(User $user, Banner $banner)
    {
        return (new Authorize($user, 'customize_appearance', $banner))->check();
    }

    /**
     * Determine whether the user can delete the Banner.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banner  $banner
     * @return mixed
     */
    public function delete(User $user, Banner $banner)
    {
        return (new Authorize($user, 'customize_appearance', $banner))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'customize_appearance'))->check();
    }
}
