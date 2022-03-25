<?php

namespace App\Repositories\DeliveryBoy;

use App\Models\DeliveryBoy;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use App\Repositories\DeliveryBoy\DeliveryBoyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentDeliveryBoy extends EloquentRepository implements BaseRepository, DeliveryBoyRepository
{
    protected $model;

    public function __construct(DeliveryBoy $deliveryBoy)
    {
        $this->model = $deliveryBoy;
    }

    public function all()
    {
        if (Auth::user()->isFromPlatform()) {
            $result = $this->model->with('shop:id,name');
        } else {
            $result = $this->model->mine();
        }

        return $result->orderBy('id', 'desc')->get();
    }

    public function trashOnly()
    {
        if (Auth::user()->isFromPlatform()) {
            return $this->model->with('shop:id,name')->onlyTrashed()->get();
        }

        return $this->model->mine()->onlyTrashed()->get();
    }

    public function store($request)
    {
        $deliveryBoy = parent::store($request);

        $deliveryBoy->saveAddress($request);

        if ($request->hasFile('image')) {
            $deliveryBoy->saveImage($request->file('image'));
        }

        return $deliveryBoy;
    }

    public function update(Request $request, $id)
    {
        $deliveryBoy = parent::update($request, $id);

        if ($request->hasFile('image')) {
            $deliveryBoy->deleteImage();
        }

        if ($request->hasFile('image')) {
            $deliveryBoy->saveImage($request->file('image'));
        }

        return $deliveryBoy;
    }

    public function destroy($id)
    {   
        $deliveryBoy = parent::findTrash($id);

        // $deliveryBoy->flushAddresses();

        $deliveryBoy->flushImages();

        return $deliveryBoy->forceDelete();
    }

    public function massTrash($ids)
    {
        $deliveryBoys = $this->model->withTrashed()->whereIn('id', $ids)->get();

        // foreach ($deliveryBoys as $deliveryBoy) {
        //     //$deliveryBoy->flushAddresses();
        //     $deliveryBoy->flushImages();
        // }

        //massTrash
        return parent::massTrash($ids);
    }

    public function massDestroy($ids)
    {
        $deliveryBoys =  $this->model->withTrashed()->whereIn('id', $ids)->get();

        foreach ($deliveryBoys as $deliveryBoy) {
            //$deliveryBoy->flushAddresses();
            $deliveryBoy->flushImages();
        }

        //massDestroy
        return parent::massDestroy($ids);
    }

    public function emptyTrash()
    {
        $deliveryBoys = $this->model->onlyTrashed()->get();

        // foreach ($deliveryBoys as $deliveryBoy) {
        //     //$deliveryBoy->flushAddresses();
        //     $deliveryBoy->flushImages();
        // }

        return parent::emptyTrash();
    }
}
