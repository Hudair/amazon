<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateCategoryGroupRequest;
use App\Http\Requests\Validations\UpdateCategoryGroupRequest;
use App\Repositories\CategoryGroup\CategoryGroupRepository;
use Illuminate\Http\Request;

class CategoryGroupController extends Controller
{
    use Authorizable;

    private $model_name;

    private $categoryGroup;

    /**
     * construct
     */
    public function __construct(CategoryGroupRepository $categoryGroup)
    {
        parent::__construct();
        $this->model_name = trans('app.model.category_group');
        $this->categoryGroup = $categoryGroup;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryGrps = $this->categoryGroup->all();

        $trashes = $this->categoryGroup->trashOnly();

        return view('admin.category.categoryGroup', compact('categoryGrps', 'trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category._createGrp');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryGroupRequest $request)
    {
        $this->categoryGroup->store($request);

        return back()->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoryGroup = $this->categoryGroup->find($id);

        return view('admin.category._editGrp', compact('categoryGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryGroupRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.category_groups', 9)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->categoryGroup->update($request, $id);

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.category_groups', 9)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->categoryGroup->trash($id);

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
        $this->categoryGroup->restore($id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model_name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->categoryGroup->destroy($id);

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

        $this->categoryGroup->massTrash($request->ids);

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

        $this->categoryGroup->massDestroy($request->ids);

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
        $this->categoryGroup->emptyTrash($request);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }
}
