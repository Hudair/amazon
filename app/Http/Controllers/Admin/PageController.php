<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreatePageRequest;
use App\Http\Requests\Validations\UpdatePageRequest;
use App\Models\Page;
use App\Repositories\Page\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    // use Authorizable;

    private $model;

    private $page;

    /**
     * construct
     */
    public function __construct(PageRepository $page)
    {
        parent::__construct();
        $this->model = trans('app.model.page');
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = $this->page->all();

        $trashes = $this->page->trashOnly();

        return view('admin.page.index', compact('pages', 'trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.page._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePageRequest $request)
    {
        $this->page->store($request);

        Cache::forget('cached_pages');

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.page._edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePageRequest $request, $id)
    {
        $this->page->update($request, $id);

        Cache::forget('cached_pages');

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        $this->page->trash($id);

        Cache::forget('cached_pages');

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
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
        $this->page->restore($id);

        Cache::forget('cached_pages');

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->page->destroy($id);

        Cache::forget('cached_pages');

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massTrash(Request $request)
    {
        $this->page->massTrash($request->ids);

        Cache::forget('cached_pages');

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.trashed', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massRestore(Request $request)
    {
        $this->page->massRestore($request->ids);

        Cache::forget('cached_pages');

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.restored', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        $this->page->massDestroy($request->ids);

        Cache::forget('cached_pages');

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        $this->page->emptyTrash($request);

        Cache::forget('cached_pages');

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }
}
