<?php

namespace App\Repositories\Packaging;

use App\Models\Packaging;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EloquentPackaging extends EloquentRepository implements BaseRepository, PackagingRepository
{
    protected $model;

    public function __construct(Packaging $packaging)
    {
        $this->model = $packaging;
    }

    public function all()
    {
        if (!Auth::user()->isFromPlatform()) {
            return $this->model->mine()->with('image')->get();
        }

        return $this->model->with('image')->all();
    }

    public function trashOnly()
    {
        if (!Auth::user()->isFromPlatform()) {
            return $this->model->mine()->with('image')->onlyTrashed()->get();
        }

        return $this->model->with('image')->onlyTrashed()->get();
    }

    public function store(Request $request)
    {
        $packaging = parent::store($request);

        if ($request->hasFile('image')) {
            $packaging->saveImage($request->file('image'));
        }

        return $packaging;
    }

    public function update(Request $request, $id)
    {
        if ((bool) $request->input('default')) {
            $this->removeDefault();
        }

        $packaging = parent::update($request, $id);

        if ($request->hasFile('image') || ($request->input('delete_image') == 1)) {
            $packaging->deleteImage();
        }

        if ($request->hasFile('image')) {
            $packaging->saveImage($request->file('image'));
        }

        return $packaging;
    }

    public function destroy($id)
    {
        $packaging = parent::findTrash($id);

        $packaging->flushImages();

        return $packaging->forceDelete();
    }

    public function massDestroy($ids)
    {
        $packagings = $this->model->withTrashed()->whereIn('id', $ids)->get();

        foreach ($packagings as $packaging) {
            $packaging->flushImages();
        }

        return parent::massDestroy($ids);
    }

    public function emptyTrash()
    {
        $packagings = $this->model->onlyTrashed()->get();

        foreach ($packagings as $packaging) {
            $packaging->flushImages();
        }

        return parent::emptyTrash();
    }

    public function removeDefault()
    {
        $default = $this->model->where('default', 1)->mine()->first();

        return $default ? $default->update(['default' => null]) : true;
    }
}
