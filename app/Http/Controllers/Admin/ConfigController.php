<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\Config;
// use App\Common\Authorizable;
use App\Events\Shop\ConfigUpdated;
use App\Events\Shop\DownForMaintainace;
use App\Events\Shop\ShopIsLive;
use App\Events\Shop\ShopUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\MerchantVerifyRequest;
use App\Http\Requests\Validations\ToggleMaintenanceModeRequest;
use App\Http\Requests\Validations\UpdateBasicConfigRequest;
use App\Http\Requests\Validations\UpdateConfigRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    // use Authorizable;

    private $model_name;

    /**
     * construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->model_name = trans('app.model.config');
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewGeneralSetting()
    {
        $shop = Shop::findOrFail(Auth::user()->merchantId());

        return view('admin.config.general', compact('shop'));
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        $config = Config::findOrFail(Auth::user()->merchantId());

        $this->authorize('view', $config); // Check permission

        return view('admin.config.index', compact('config'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateBasicConfig(UpdateBasicConfigRequest $request, $id)
    {
        $config = Config::findOrFail($id);

        // echo "<pre>"; print_r($request->all()); echo "<pre>"; exit('end!');
        if (config('app.demo') == true && $config->shop_id <= config('system.demo.shops', 2)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->authorize('update', $config); // Check permission

        $config->shop->update($request->all());

        event(new ShopUpdated($config->shop));

        if ($request->hasFile('logo') || ($request->input('delete_logo') == 1)) {
            $config->shop->deleteLogo();
        }

        if ($request->hasFile('logo')) {
            $config->shop->saveImage($request->file('logo'), 'logo');
        }

        if ($request->hasFile('cover_image') || ($request->input('delete_cover_image') == 1)) {
            $config->shop->deleteCoverImage();
        }

        if ($request->hasFile('cover_image')) {
            $config->shop->saveImage($request->file('cover_image'), 'cover');
        }

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    public function updateConfig(UpdateConfigRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.shops', 2)) {
            return response('error', 444);
        }

        $config = Config::findOrFail($id);

        $this->authorize('update', $config); // Check permission

        if ($config->update($request->all())) {
            event(new ConfigUpdated($config->shop, Auth::user()));

            return response('success', 200);
        }

        return response('error', 405);
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(MerchantVerifyRequest $request)
    {
        $config = Config::findOrFail(Auth::user()->merchantId());

        return view('admin.config.verify', compact('config'));
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveVerificationData(MerchantVerifyRequest $request)
    {
        $config = Config::findOrFail(Auth::user()->merchantId());

        if ($request->hasFile('documents')) {
            $config->saveAttachments($request->file('documents'));
        }

        $config->update(['pending_verification' => 1]);

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    /**
     * Toggle Maintenance Mode of the given id, Its uses the ajax middleware
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $node
     * @return \Illuminate\Http\Response
     */
    public function toggleNotification(Request $request, $node)
    {
        $config = Config::findOrFail($request->user()->merchantId());

        if (config('app.demo') == true && $config->shop_id <= config('system.demo.shops', 2)) {
            return response('error', 444);
        }

        $this->authorize('update', $config); // Check permission

        $config->$node = !$config->$node;

        if ($config->save()) {
            event(new ConfigUpdated($config->shop, Auth::user()));

            return response('success', 200);
        }

        return response('error', 405);
    }

    /**
     * Toggle Maintenance Mode of the given id, Its uses the ajax middleware
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleMaintenanceMode(ToggleMaintenanceModeRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.shops', 2)) {
            return response('error', 444);
        }

        $config = Config::findOrFail($id);

        $this->authorize('update', $config); // Check permission

        $config->maintenance_mode = !$config->maintenance_mode;

        if ($config->save()) {
            if ($config->maintenance_mode) {
                event(new DownForMaintainace($config->shop));
            } else {
                event(new ShopIsLive($config->shop));
            }

            return response('success', 200);
        }

        return response('error', 405);
    }
}
