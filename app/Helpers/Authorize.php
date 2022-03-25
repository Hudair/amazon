<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

/**
 * Check the action athentications
 */
class Authorize
{
    protected $user;

    protected $model;

    protected $slug;

    public function __construct($user, $slug, $model = null)
    {
        $this->user = $user;
        $this->model = $model;
        $this->slug = $slug;
    }

    /**
     * Check authorization
     *
     * @return bool
     */
    public function check()
    {
        // return true; //FOR TEMPORARY TEST
        if ($this->isExceptional()) {
            return true;
        }

        // Deny the action immediately if the model has shop_id field and user from different shop

        if (
            isset($this->model) &&
            (isset($this->model->shop_id) || is_array($this->model) && array_key_exists('shop_id', $this->model)) &&
            !Auth::user()->isFromPlatform() &&
            !$this->merchantAuth()
        ) {
            return false;
        }

        return in_array($this->slug, $this->permissionSlugs());
    }

    /**
     * Check if the user's shop_id and model's shop_id is same
     *
     * @return bool
     */
    private function merchantAuth()
    {
        return isset($this->model->shop_id) && $this->model->shop_id == $this->user->merchantId();
    }

    /**
     * Some case in special conditions you may allow all actions for the user
     *
     * @return bool
     */
    private function isExceptional()
    {
        // Some routes only shows personalized information and allow access
        if (in_array($this->slug, ['dashboard', 'profile', 'verify', 'secretLogout'])) {
            return true;
        }

        // The Super admin will not required to check authorization.
        if (Auth::user()->isSuperAdmin()) {
            // Just avoid the merchant modules to keep the dashboard clean
            if (isset(config('authSlugs')[$this->slug])) {
                return config('authSlugs')[$this->slug] != 'Merchant';
            }

            return true;
        }

        // The admin will not required to check authorization for admin modules.
        if (Auth::user()->isAdmin()) {
            $admin_modules = [
                'currency',
                'country',
            ];

            $temp = explode('_', $this->slug);

            if (!empty($temp[1]) && in_array($temp[1], $admin_modules)) {
                return true;
            }
        }

        // The content creator always have the full permission
        if (isset($this->model->user_id) && $this->model->user_id == $this->user->id) {
            return true;
        }

        return false;
    }

    /**
     * If the logged in user is the same user to check the authorization,
     * then return permission from config veriable that sets by the initSettings middleware.
     * Otherwise get the permission from the database.
     *
     * @return arr
     */
    private function permissionSlugs()
    {
        // For current user just return permissions from cinfig
        if (Auth::guard('web')->user()->id == $this->user->id) {
            return config('permissions');
        }

        return $this->user->role->permissions()->pluck('slug')->toArray();
    }
}
