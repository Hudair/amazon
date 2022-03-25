<?php

namespace App\Repositories\Supplier;

use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EloquentSupplier extends EloquentRepository implements BaseRepository, SupplierRepository
{
    protected $model;

    public function __construct(Supplier $supplier)
    {
        $this->model = $supplier;
    }

    public function all()
    {
        if (!Auth::user()->isFromPlatform()) {
            return $this->model->mine()->with('primaryAddress', 'image')->get();
        }

        return $this->model->with('primaryAddress', 'image')->get();
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
        $supplier = parent::store($request);

        $this->saveAdrress($request->all(), $supplier);

        if ($request->hasFile('image')) {
            $supplier->saveImage($request->file('image'));
        }

        return $supplier;
    }

    public function update(Request $request, $id)
    {
        $supplier = parent::update($request, $id);

        if ($request->hasFile('image') || ($request->input('delete_image') == 1)) {
            $supplier->deleteImage();
        }

        if ($request->hasFile('image')) {
            $supplier->saveImage($request->file('image'));
        }

        return $supplier;
    }

    public function destroy($id)
    {
        $supplier = parent::findTrash($id);

        $supplier->flushAddresses();

        $supplier->flushImages();

        return $supplier->forceDelete();
    }

    public function massDestroy($ids)
    {
        $suppliers = $this->model->withTrashed()->whereIn('id', $ids)->get();

        foreach ($suppliers as $supplier) {
            $supplier->flushAddresses();
            $supplier->flushImages();
        }

        return parent::massDestroy($ids);
    }

    public function emptyTrash()
    {
        $suppliers = $this->model->onlyTrashed()->get();

        foreach ($suppliers as $supplier) {
            $supplier->flushAddresses();
            $supplier->flushImages();
        }

        return parent::emptyTrash();
    }

    public function saveAdrress(array $address, $supplier)
    {
        $supplier->addresses()->create($address);
    }
}
