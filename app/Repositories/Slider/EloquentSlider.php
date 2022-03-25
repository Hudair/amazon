<?php

namespace App\Repositories\Slider;

use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use App\Models\Slider;
use Illuminate\Http\Request;

class EloquentSlider extends EloquentRepository implements BaseRepository, SliderRepository
{
    protected $model;

    public function __construct(Slider $slider)
    {
        $this->model = $slider;
    }

    public function all()
    {
        return $this->model->with('featureImage', 'mobileImage')->orderBy('order', 'asc')->get();
    }

    public function destroy($id)
    {
        $slider = parent::find($id);

        $slider->flushImages();

        return $slider->forceDelete();
    }

    public function massDestroy($ids)
    {
        foreach ($ids as $id) {
            $this->destroy($id);
        }
    }
}
