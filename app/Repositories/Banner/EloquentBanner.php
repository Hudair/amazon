<?php

namespace App\Repositories\Banner;

use App\Models\Banner;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;

class EloquentBanner extends EloquentRepository implements BaseRepository, BannerRepository
{
    protected $model;

    public function __construct(Banner $banner)
    {
        $this->model = $banner;
    }

    public function all()
    {
        return $this->model->with('featureImage', 'mobileImage')
            ->orderBy('order', 'asc')->get();
    }

    public function destroy($id)
    {
        $banner = parent::find($id);

        $banner->flushImages();

        return $banner->forceDelete();
    }

    public function massDestroy($ids)
    {
        foreach ($ids as $id) {
            $this->destroy($id);
        }
    }
}
