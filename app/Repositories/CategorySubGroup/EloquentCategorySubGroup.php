<?php

namespace App\Repositories\CategorySubGroup;

use App\Models\CategorySubGroup;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;

class EloquentCategorySubGroup extends EloquentRepository implements BaseRepository, CategorySubGroupRepository
{
    protected $model;

    public function __construct(CategorySubGroup $categorySubGroup)
    {
        $this->model = $categorySubGroup;
    }

    public function all()
    {
        return $this->model->with(
            'group:id,name,deleted_at',
            'coverImage'
        )->withCount('categories')->get();
    }

    public function trashOnly()
    {
        return $this->model->with('group:id,name,deleted_at')->onlyTrashed()->get();
    }

    public function store(Request $request)
    {
        $category = parent::store($request);

        /* if ($request->hasFile('image'))
            $category->saveImage($request->file('image'), true);*/

        return $category;
    }

    public function update(Request $request, $id)
    {
        $category = parent::update($request, $id);

        /*   if ($request->hasFile('image') || ($request->input('delete_image') == 1))
            $category->deleteFeaturedImage();

        if ($request->hasFile('image'))
            $category->saveImage($request->file('image'), true);*/

        return $category;
    }

    public function destroy($id)
    {
        $category = parent::findTrash($id);

        $category->flushImages();

        return $category->forceDelete();
    }

    public function massDestroy($ids)
    {
        $categories = $this->model->withTrashed()->whereIn('id', $ids)->get();

        foreach ($categories as $category) {
            $category->flushImages();
        }

        return parent::massDestroy($ids);
    }

    public function emptyTrash()
    {
        $categories = $this->model->onlyTrashed()->get();

        foreach ($categories as $category) {
            $category->flushImages();
        }

        return parent::emptyTrash();
    }
}
