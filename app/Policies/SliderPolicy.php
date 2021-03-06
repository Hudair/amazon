<?php

namespace App\Policies;

use App\Helpers\Authorize;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SliderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view Sliders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slider  $slider
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'customize_appearance'))->check();
    }

    /**
     * Determine whether the user can view the Slider.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slider  $slider
     * @return mixed
     */
    public function view(User $user, Slider $slider)
    {
        return (new Authorize($user, 'customize_appearance', $slider))->check();
    }

    /**
     * Determine whether the user can create Sliders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'customize_appearance'))->check();
    }

    /**
     * Determine whether the user can update the Slider.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slider  $slider
     * @return mixed
     */
    public function update(User $user, Slider $slider)
    {
        return (new Authorize($user, 'customize_appearance', $slider))->check();
    }

    /**
     * Determine whether the user can delete the Slider.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slider  $slider
     * @return mixed
     */
    public function delete(User $user, Slider $slider)
    {
        return (new Authorize($user, 'customize_appearance', $slider))->check();
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
