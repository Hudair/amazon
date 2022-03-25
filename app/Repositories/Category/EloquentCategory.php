<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;

class EloquentCategory extends EloquentRepository implements BaseRepository, CategoryRepository
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function all()
    {
        return $this->model->with(
            'subGroup:id,name,category_group_id,deleted_at',
            'subGroup.group:id,name,deleted_at',
            'featureImage',
            'coverImage'
        )->withCount('products', 'listings')->get();
    }

    public function trashOnly()
    {
        return $this->model->with(
            'subGroup:id,name,category_group_id,deleted_at',
            'subGroup.group:id,name,deleted_at'
        )->onlyTrashed()->get();
    }

    //Create Category
    public function store(Request $request)
    {
        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        return parent::update($request, $id);
    }

    public function destroy($id)
    {
        $category = parent::findTrash($id);

        $category->flushImages();

        return $category->forceDelete();
    }

    public function massDestroy($ids)
    {
        $catSubGrps = $this->model->withTrashed()->whereIn('id', $ids)->get();

        foreach ($catSubGrps as $catSubGrp) {
            $catSubGrp->flushImages();
        }

        return parent::massDestroy($ids);
    }

    public function emptyTrash()
    {
        $catSubGrps = $this->model->onlyTrashed()->get();

        foreach ($catSubGrps as $catSubGrp) {
            $catSubGrp->flushImages();
        }

        return parent::emptyTrash();
    }
}
