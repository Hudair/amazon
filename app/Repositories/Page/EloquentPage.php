<?php

namespace App\Repositories\Page;

use App\Models\Page;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;

class EloquentPage extends EloquentRepository implements BaseRepository, PageRepository
{
    protected $model;

    public function __construct(Page $page)
    {
        $this->model = $page;
    }

    public function all()
    {
        return $this->model->with('coverImage')->get();
    }

    public function trashOnly()
    {
        return $this->model->onlyTrashed()->get();
    }

    //Create Page
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
        $page = parent::findTrash($id);

        $page->flushImages();

        return $page->forceDelete();
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
