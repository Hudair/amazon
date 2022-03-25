<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Models\Config;
use App\Events\Shop\ShopDeleted;
use App\Events\Shop\ShopUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\UpdateShopRequest;
use App\Repositories\Shop\ShopRepository;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    use Authorizable;

    private $model_name;

    private $shop;

    /**
     * construct
     */
    public function __construct(ShopRepository $shop)
    {
        parent::__construct();

        $this->model_name = trans('app.model.shop');
        $this->shop = $shop;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shops = $this->shop->all();

        $trashes = $this->shop->trashOnly();

        return view('admin.shop.index', compact('shops', 'trashes'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function staffs($id)
    {
        $shop = $this->shop->find($id);

        $staffs = $this->shop->staffs($shop);

        $trashes = $this->shop->staffsTrashOnly($shop);

        return view('admin.shop.staffs', compact('shop', 'staffs', 'trashes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shop = $this->shop->find($id);

        return view('admin.shop._show', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop = $this->shop->find($id);

        return view('admin.shop._edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShopRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.shops', 2)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $shop = $this->shop->update($request, $id);

        if (
            $request->has('remove_from_pending_verification_list') &&
            $request->remove_from_pending_verification_list == 1
        ) {
            $shop->config->update(['pending_verification' => null]);
        }

        event(new ShopUpdated($shop));

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    /**
     * [toggleStatus description]
     *
     * @param  Request $request
     * @param  [type]  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Request $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.shops', 2)) {
            return response('error', 444);
        }

        $shop = $this->shop->find($id);

        $shop->active = ! $shop->active;

        if ($shop->save()) {
            event(new ShopUpdated($shop));

            return response('success', 200);
        }

        return response('error', 405);
    }

    /**
     * [verifications description]
     *
     * @param  Request $request [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function verifications(Request $request)
    {
        $merchants = Config::where('pending_verification', 1)
            ->with(['shop.logo', 'attachments' => function ($query) {
                $query->orderBy('updated_at', 'desc');
            }])->get();

        return view('admin.shop.verifications', compact('merchants'));
    }

    /**
     * [showVerificationForm description]
     *
     * @param  Request $request
     * @param  Shop    $shop
     *
     * @return \Illuminate\Http\Response
     */
    public function showVerificationForm(Request $request, Shop $shop)
    {
        return view('admin.shop._verify', compact('shop'));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.shops', 2)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->shop->trash($id);

        event(new ShopDeleted($id));

        return back()->with('success', trans('messages.trashed', ['model' => $this->model_name]));
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        $this->shop->restore($id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model_name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->shop->destroy($id);

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massTrash(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->shop->massTrash($request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.trashed', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.trashed', ['model' => $this->model_name]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->shop->massDestroy($request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        $this->shop->emptyTrash($request);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }
}
