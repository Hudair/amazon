<?php

namespace App\Repositories\Merchant;

use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EloquentMerchant extends EloquentRepository implements BaseRepository, MerchantRepository
{
    protected $model;

    public function __construct(User $merchant)
    {
        $this->model = $merchant;
    }

    public function all()
    {
        return $this->model->where('role_id', Role::MERCHANT)
            ->with(
                'owns',
                'owns.config:shop_id,maintenance_mode',
                'owns.plan:name,plan_id',
                'owns.logoImage',
                'image',
                'primaryAddress'
            )->get();
    }

    public function trashOnly()
    {
        return $this->model->where('role_id', Role::MERCHANT)
            ->with('owns', 'image')->onlyTrashed()->get();
    }

    public function addresses($merchant)
    {
        return $merchant->addresses()->get();
    }

    public function store(Request $request)
    {
        $merchant = parent::store($request);

        $this->saveAdrress($request->all(), $merchant);

        if ($request->hasFile('image')) {
            $merchant->saveImage($request->file('image'));
        }

        return $merchant;
    }

    public function update(Request $request, $id)
    {
        $merchant = parent::update($request, $id);

        if ($request->hasFile('image') || ($request->input('delete_image') == 1)) {
            $merchant->deleteImage();
        }

        if ($request->hasFile('image')) {
            $merchant->saveImage($request->file('image'));
        }

        return $merchant;
    }

    public function destroy($id)
    {
        $merchant = parent::findTrash($id);

        $merchant->flushAddresses();

        $merchant->flushImages();

        return $merchant->forceDelete();
    }

    public function saveAdrress(array $address, $merchant)
    {
        $merchant->addresses()->create($address);
    }
}
