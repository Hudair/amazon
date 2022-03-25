<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateBannerRequest;
use App\Http\Requests\Validations\UpdateBannerRequest;
use App\Repositories\Banner\BannerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    use Authorizable;

    private $model;

    private $banner;

    /**
     * construct
     */
    public function __construct(BannerRepository $banner)
    {
        parent::__construct();

        $this->banner = $banner;

        $this->model = trans('app.model.banner');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::with('group', 'featuredImage', 'images')->orderBy('group_id', 'asc')->get();

        return view('admin.banner.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banner._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBannerRequest $request)
    {
        $this->banner->store($request);

        // Clear banners from cache
        Cache::forget('banners');

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        return view('admin.banner._edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBannerRequest $request, $banner)
    {
        $this->banner->update($request, $banner);

        // Clear banners from cache
        Cache::forget('banners');

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $banner->flushImages();

        $banner->forceDelete();

        // Clear banners from cache
        Cache::forget('banners');

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        $this->banner->massDestroy($request->ids);

        // Clear banners from cache
        Cache::forget('banners');

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }
}
