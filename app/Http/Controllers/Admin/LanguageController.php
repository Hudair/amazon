<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateLanguageRequest;
use App\Http\Requests\Validations\UpdateLanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LanguageController extends Controller
{
    private $model;

    /**
     * construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = trans('app.language');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::orderBy('active', 'desc')->orderBy('order', 'asc')->get();

        $trashes = Language::onlyTrashed()->get();

        return view('admin.language.index', compact('languages', 'trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.language._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLanguageRequest $request)
    {
        $language = Language::create($request->all());

        // Clear active_locales from cache
        Cache::forget('active_locales');

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {
        return view('admin.language._edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLanguageRequest $request, Language $language)
    {
        if (config('app.demo') == true && $language->id <= config('system.demo.langs', 4)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $language->update($request->all());

        // Clear active_locales from cache
        Cache::forget('active_locales');

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, Language $language)
    {
        if (config('app.demo') == true && $language->id <= config('system.demo.langs', 4)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $language->delete();

        // Clear active_locales from cache
        Cache::forget('active_locales');

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
        Language::onlyTrashed()->findOrFail($id)->restore();

        // Clear active_locales from cache
        Cache::forget('active_locales');

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
        if (config('app.demo') == true && $id <= config('system.demo.langs', 4)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $language = Language::onlyTrashed()->findOrFail($id);

        $language->forceDelete();

        // Clear active_locales from cache
        Cache::forget('active_locales');

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
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        Language::whereIn('id', $request->ids)->delete();

        // Clear active_locales from cache
        Cache::forget('active_locales');

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
        Language::onlyTrashed()->whereIn('id', $request->ids)->restore();

        // Clear active_locales from cache
        Cache::forget('active_locales');

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
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        Language::withTrashed()->whereIn('id', $request->ids)->forceDelete();

        // Clear active_locales from cache
        Cache::forget('active_locales');

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
        Language::onlyTrashed()->forceDelete();

        // Clear active_locales from cache
        Cache::forget('active_locales');

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }
}
