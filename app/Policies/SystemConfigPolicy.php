<?php

namespace App\Policies;

use App\Helpers\Authorize;
use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemConfigPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view system.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return (new Authorize($user, 'view_system_config'))->check();
    }

    /**
     * Determine whether the user can update the system.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\System  $system
     * @return mixed
     */
    public function update(User $user, SystemConfig $system)
    {
        return (new Authorize($user, 'edit_system_config', $system))->check();
    }
}
