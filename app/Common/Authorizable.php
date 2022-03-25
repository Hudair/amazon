<?php

namespace App\Common;

use App\Helpers\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

/**
 * Attach this Trait to a User (or other model) for easier read/writes on Addresses
 *
 * @author Munna Khan
 */
trait Authorizable
{
    private $abilities = [
        'index'             => 'view',
        'invoice'           => 'view',
        'show'              => 'view',
        'view'              => 'view',
        'staffs'            => 'view',
        'entities'          => 'view',
        'labelOf'           => 'view',
        'statusOf'          => 'view',
        'general'           => 'view',
        'pickup'            => 'view',
        'create'            => 'add',
        'add'               => 'add',
        'store'             => 'add',
        'draftSend'         => 'add',
        'find'               => 'add',
        'addWithVariant'    => 'add',
        'storeWithVariant'  => 'add',
        'showSearchForm'    => 'add',
        'search'            => 'add',
        'restore'           => 'add',
        'massRestore'       => 'add',
        'fulfill'            => 'fulfill',
        'updateOrderStatus' => 'fulfill',
        'togglePaymentStatus' => 'fulfill',
        'saveAdminNote'     => 'fulfill',
        'cancellation'      => 'cancel',
        'initiate'          => 'initiate',
        'form'              => 'initiate',
        'approve'           => 'approve',
        'decline'           => 'approve',
        'storeReply'        => 'reply',
        'reply'             => 'reply',
        'response'          => 'response',
        'storeResponse'     => 'response',
        'edit'              => 'edit',
        'update'            => 'edit',
        'massUpdate'        => 'edit',
        'removeCountry'     => 'edit',
        'editStates'        => 'edit',
        'updateStates'      => 'edit',
        'updateQtt'         => 'edit',
        'updatePassword'    => 'edit',
        'toggle'            => 'edit',
        'verifications'      => 'edit',
        'delete'            => 'delete',
        'trash'             => 'delete',
        'archive'           => 'archive',
        'destroy'           => 'delete',
        'massDestroy'       => 'delete',
        'massTrash'         => 'delete',
        'emptyTrash'        => 'delete',
        'secretLogin'       => 'login',
        'assign'            => 'assign',
    ];

    /**
     * List of modules that grouped into a common module named vendor modules
     * This will help to set the role permissions
     *
     * @var arr
     */
    private $vendor_modules = [
        'shop',
        'merchant',
    ];

    /**
     * List of modules that grouped into a common module named utility modules
     * This will help to set the role permissions
     *
     * @var arr
     */
    private $utility_modules = [
        'faq',
        'emailTemplate',
        'faqTopic',
        'blog',
        'event',
        'page',
    ];

    /**
     * List of modules that grouped into a common module named apprearance modules
     * This will help to set the role permissions
     *
     * @var arr
     */
    private $appearance_modules = [
        'theme',
        'banner',
        'slider',
        'custom_css',
        'appearance',
    ];

    /**
     * List of modules that has exceptional permission than the abilities above
     * This will help to set the role permissions
     *
     * @var arr
     */
    private $update_exception_modules = [
        'message',
        'ticket',
        'refund',
    ];

    /**
     * Override of callAction to perform the authorization before
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
        if (!$this->checkPermission('', $parameters)) {
            return view('errors.forbidden');
        }

        return parent::callAction($method, $parameters);
    }

    /**
     * checkPermission for this action with the given slug.
     * If the logged in user is the Super Admin OR
     * the given slug is 'dashboard' then no need to check the permission
     *
     * @param  string $slug
     *
     * @return bool false if the permission not granted
     */
    private function checkPermission($slug = '', $model = null)
    {
        if (Request::ajax()) {
            return true;
        }

        $slug = (bool) $slug ? $slug : $this->getSlug();

        // dd($slug);
        // The Super admin will not required to check authorization.
        // if (Auth::user()->isAdmin()) {

        //     return true;
        // }

        // For inspectable
        if (in_array($slug, ['edit_inventory']) && Auth::user()->isFromPlatform()) {
            return true;
        }

        // Merge assign_deliveryboy permission into fulfill
        if ($slug == 'assign_deliveryboy') {
            $slug = 'fulfill_order';
        }

        return (new Authorize(Auth::user(), $slug, $model))->check();
    }

    /**
     * Get the slug to check the action permission
     *
     * @return string $slug
     */
    private function getSlug($action = null, $module = null)
    {
        $temp1 = explode('.', Request::route()->getName());
        $module = $module ? $module : array_slice($temp1, -2, 1)[0];
        $action = $action ? $action : array_slice($temp1, -1, 1)[0];

        if ($this->isVendor($module)) {
            return $this->abilities[$action] . '_vendor';
        }

        if ($this->isUtility($module)) {
            return $this->abilities[$action] . '_utility';
        }

        if ($this->isAppearance($module)) {
            return 'customize_appearance';
        }

        if ('update' == $action) {
            if ($this->isUpdateException($module)) {
                return $action . '_' . Str::snake($module);
            }
        }

        return array_key_exists($action, $this->abilities) ? $this->abilities[$action] . '_' . Str::snake($module) : $action;
    }

    /**
     * Check if module is an Vendor module.
     *
     * @param  string  $module
     *
     * @return bool
     */
    private function isVendor($module)
    {
        return in_array($module, $this->vendor_modules);
    }

    /**
     * Check if module is an utility module.
     *
     * @param  string  $module
     *
     * @return bool
     */
    private function isUtility($module)
    {
        return in_array($module, $this->utility_modules);
    }

    private function isAppearance($module)
    {
        return in_array($module, $this->appearance_modules);
    }

    /**
     * Check if module is an Support module.
     *
     * @param  string  $module
     *
     * @return bool
     */
    private function isUpdateException($module)
    {
        return in_array($module, $this->update_exception_modules);
    }
}
